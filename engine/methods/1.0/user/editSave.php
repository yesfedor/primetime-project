<?php
$user_args = [
  'name' => $args['name'],
  'surname' => $args['surname'],
  'birthday' => $args['birthday'],
  'gender' => $args['gender']
];

$responce = UserEditSave($args['jwt'], $user_args);