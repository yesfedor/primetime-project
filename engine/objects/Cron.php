<?php
function CronUserFeedLoader (int $limit = 500, int $offset = 0) {
  // Get users list
  $query_user = "SELECT uid, telegram, uid_crypt FROM User WHERE uid > :offset ORDER BY uid LIMIT $limit";
  $var_user = [
    ':offset' => $offset
  ];
  $user = dbGetAll($query_user, $var_user);

  $global = [];

  $time = time();
  // $skipTime = 6 * 60 * 60 - 1;
  $skipTime = 14 * 24 * 60 * 60 - 1;

  for ($i = 0; $i < count($user); $i++) {
    $uid = $user[$i]['uid'];
    $telegram = $user[$i]['telegram'];
    $uid_crypt = $user[$i]['uid_crypt'];

    $apiFeedPath = "https://primetime.su/api/method/watch.getFeed?v=1.0&&jwt=system&client_id=system&system=807a4af6&uid=$uid&silent=0";
    $feedData = json_decode(file_get_contents($apiFeedPath), true);

    if ($feedData['total'] <= 0) {
      continue;
    }

    $feedContent = $feedData['content'];
    $timeByKinopoiskId = [];

    for ($ii = 0; $ii < count($feedContent); $ii++) {
      $kinopoiskId = $feedContent[$ii]['kinopoiskId'];
      $timeContent = $feedContent[$ii]['time'];
      if ($time > ($timeContent + $skipTime)) {
        continue;
      }

      $timeByKinopoiskId[$kinopoiskId] = $timeContent;
    }

    foreach ($timeByKinopoiskId as $itemKinopoiskId => $itemTime) {
      // узнать есть ли подписка на рассылку
      if (!$telegram) {
        continue;
      }

      $query_notify_cache = "SELECT id, time FROM WatchFeedNotifyCache WHERE uid = :uid and kinopoiskId = :kinopoiskId";
      $var_notify_cache = [
        ':uid' => $uid,
        ':kinopoiskId' => $itemKinopoiskId
      ];
      $notifyCache = dbGetOne($query_notify_cache, $var_notify_cache);
      
      // проверить, есть ли в базе по uid & kinopoiskId время последего timeContent
      if (!$notifyCache['id']) {
        // если нет, то сделать рассылку, создать запись с меткой последего timeContent
        $global[$uid][] = CronUserFeedLoaderSender($uid, $telegram, $uid_crypt, $itemKinopoiskId, $feedData['binding'][$itemKinopoiskId]);
        
        $query_update_notify_cache = "INSERT INTO `WatchFeedNotifyCache`(`id`, `uid`, `kinopoiskId`, `time`) VALUES (NULL, :uid, :kinopoiskId, :time)";
        $var_update_notify_cache = [
          ':uid' => $uid,
          ':kinopoiskId' => $itemKinopoiskId,
          ':time' => $itemTime
        ];
        dbAddOne($query_update_notify_cache, $var_update_notify_cache);
      } else {
        // если есть, проверить метку последего timeContent, если она меньше текущей, сделать рассылку, обновить запись в базе по uid & kinopoiskId
        if ($itemTime > $notifyCache['time']) {
          $global[$uid][] = CronUserFeedLoaderSender($uid, $telegram, $uid_crypt, $itemKinopoiskId, $feedData['binding'][$itemKinopoiskId]);

          $query_update_notify_cache = "UPDATE WatchFeedNotifyCache SET time = :time WHERE id = :id";
          $var_update_notify_cache = [
            ':id' => $notifyCache['id'],
            ':time' => $itemTime
          ];
          dbAddOne($query_update_notify_cache, $var_update_notify_cache);
        }
      }
    }
  } 

  // set feed cache

  // notify if has new

  // return ok

  return [
    'status' => true,
    'global' => $global
  ];
}

function CronUserFeedLoaderSender (int $uid, int $telegram, $uid_crypt, int $kinopoiskId, array $extendData) {
  $system_crypt = TelegramGetCrypt($uid);

  if (!$system_crypt) {
    $system_crypt = 'false-1';
  }
  if (!$uid_crypt) {
    $uid_crypt = 'false-2';
  }

  if (hash_equals($system_crypt['crypt'], $uid_crypt)) {
    TelegramSendMessage($telegram, 'Привет. У сериала ' . $extendData['nameRu'] . ' вышла новая серия. Смотреть: ' . 'https://primetime.su/watch' . $kinopoiskId);
  }

  return [
    $uid,
    $telegram,
    $kinopoiskId,
    $extendData
  ];
}