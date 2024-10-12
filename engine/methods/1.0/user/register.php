<?php
if (DeviceIsExistByClientId($args['client_id'])) {
  $responce = UserCreate($args['name'], $args['surname'], $args['gender'], $args['birthday'], $args['email'], $args['password']);
  if ($responce['uid']) {
    DeviceClientIdSetPermission($args['client_id'], 'allow');
    DeviceClientIdSetUid($args['client_id'], $responce['uid']);
    unset($responce['uid']);
  }
} else {
  errorHandler('AppRouterMethod.client_id_deprecated');
}