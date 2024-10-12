<?php
$to = 'contact@purplex.ru'; // кому отправляем
$from_user = 'Работа с клиентами'; // Пользователь, которому отправляем
$from_email = 'no-reply@purplex.ru'; // mail c которого отправляем
$subject = 'Заявка на услуги Purple X'; // Заголовок (тема)
$message = '
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка на услуги Purple X</title>
    <style>
    html, body {
      margin: 0px;
      padding: 0px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    main {
      text-align: center;
    }
    </style>
  </head>
  <body>
    <main>
      <h1>Заявка на услуги Purple X</h1>
      <p>
        <strong>Почта заявителя</strong> - '.$args['email'].'<br>
        <strong>Какое Юр/Физ лицо представляют</strong> - '.$args['who'].'<br>
        <strong>Услуга</strong> - '.$args['service'].'<br>
      </p>
    </main>
  </body>
</html>
';

$responce['status'] = ApiMail($to, $from_user, $from_email, $subject, $message);
?>