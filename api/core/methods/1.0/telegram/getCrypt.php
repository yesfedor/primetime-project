<?php
$user = UserJwtDecode($args['jwt'])['data'];
$responce = TelegramGetCrypt($user['uid']);
