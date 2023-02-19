<?php
function TelegramWebhook () {
  $log = true;
  $message = $_POST['message'];
  $from = $message['from'];
  $username = $from['username'];
  $chat = $message['chat'];
  $chatId = $chat['id'];
  $text = $message['text'];
  $eMsg = 'false';

  try {
    if (!$chatId) {
      return ['bad'];
    }

    switch ($text) {
      case '/start':
        TelegramSendMessage($chatId, 'Отправь мне код со своего профиля на https://iny.su/telegram-code, чтобы я мог присылать новые серии');
        break;
      case substr($text, 0, 5) === '/code':
        $textArray = explode(' ', $text);
        $code = $textArray[1];
        if ($code) {
          $query_select_user_by_uid_crypt = "SELECT uid, name, telegram, uid_crypt FROM User WHERE uid_crypt = :uid_crypt";
          $var_select_user_by_uid_crypt = [
            ':uid_crypt' => $code
          ];
          $user_by_uid_crypt = dbGetOne($query_select_user_by_uid_crypt, $var_select_user_by_uid_crypt);
          if ($user_by_uid_crypt['uid'] && $user_by_uid_crypt['uid_crypt']) {
            $query_update_telegram_chat_id = "UPDATE User SET telegram = :chatId WHERE uid_crypt = :uid_crypt";
            $var_update_telegram_chat_id = [
              ':uid_crypt' => $user_by_uid_crypt['uid_crypt'],
              ':chatId' => $chatId
            ];
            dbAddOne($query_update_telegram_chat_id, $var_update_telegram_chat_id);
            TelegramSendMessage($chatId, 'Привет, ' . $user_by_uid_crypt['name'] . '. Теперь ты будешь получать от меня нопоминания о новых сериях, не забудь подписаться на любимые сериалы!');
          } else {
            TelegramSendMessage($chatId, 'Такого пользователя нет в базе https://iny.su, авторизуйтесь и получите свой код для бота тут https://iny.su/telegram-code');
          }
        }
        break;
      case '/stop':
        break;
    }
  } catch (Exception $e) {
    $eMsg = $e->getMessage();
  }

  if ($log) {
    $fileLog = DIR . '/core/telegram.log';
    $message = '---Start
$chatId: '. $chatId .'
$text: '. $text .'
$from: '.json_encode($from).'
$user_by_uid_crypt: '.json_encode($user_by_uid_crypt).'
$eMsg: '.$eMsg.'
$_POST: '.json_encode($_POST).'
End--';
    file_put_contents($fileLog, $message);
  }
  return ['ok'];
}

function TelegramSendMessage($chatId, $messaggio) {
  $TelegramTokenBot = '5567228138:AAFYDnXdhRbPWvWL-bcHAryE2iRxKQ_QJ2g';
  $url = "https://api.telegram.org/bot" . $TelegramTokenBot . "/sendMessage?chat_id=" . $chatId;
  $url = $url . "&text=" . urlencode($messaggio);
  $ch = curl_init();
  $optArray = array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true
  );
  curl_setopt_array($ch, $optArray);
  $result = curl_exec($ch);
  curl_close($ch);

  return $result;
}

function TelegramGetCrypt($uid) {
  $system_crypt = crypt(strval($uid), 'uidCryptCodeByUser');
  $query_select = "SELECT uid, uid_crypt FROM User WHERE uid = :uid";
  $var_select = [
    ':uid' => $uid
  ];
  $user = dbGetOne($query_select, $var_select);

  $query_update = "UPDATE User SET uid_crypt = :uid_crypt WHERE uid = :uid";
  $var_update = [
    ':uid' => $user['uid'],
    ':uid_crypt' => $system_crypt
  ];

  if (!$user['uid']) {
    return ['crypt' => false];
  }

  if (!$system_crypt) {
    $system_crypt = 'false-3';
  }

  if ($user['uid_crypt']) {
    if (hash_equals($user['uid_crypt'], $system_crypt)) {
      return ['crypt' => $user['uid_crypt']];
    }
  }

  dbAddOne($query_update, $var_update);
  return $system_crypt;
}
