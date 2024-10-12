<?php
$user = UserJwtDecode($args['jwt'])['data'];
$status = (string) $args['status'] ? $args['status'] : '';
$responce = UserUpdateStatus($user['uid'], $status);