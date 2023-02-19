<?php
$responce = ApiMail('join@ecwid.com', 'Фёдор Гаранин', 'yesfedor.go@gmail.com', 'Заявка с сайта', '
<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  </head>
  <body>
    <div>Имя: '.htmlspecialchars($args['name']).'</div>
    <div>Почта: <a href="mailto:'.htmlspecialchars($args['email']).'">'.htmlspecialchars($args['email']).'</a></div>
    <div>Телефон: <a href="tel:'.htmlspecialchars($args['tel']).'">'.htmlspecialchars($args['tel']).'</a></div>
    <div>Дата рождения: '.htmlspecialchars($args['birthDate']).'</div>
    <div>Вопрос: '.htmlspecialchars($args['question']).'</div>
    <br>
    <div>Гитхаб проекта: <a href="https://github.com/yesfedor/test-task-ecwid">https://github.com/yesfedor/test-task-ecwid</a></div>
    <div>Выполнил: <a href="https://fedorgaranin.ru/">Гаранин Фёдор</a>, TG: <a href="tg://resolve?domain=yesfedor">@yesfedor</a></div>
  </body>
</html>
');