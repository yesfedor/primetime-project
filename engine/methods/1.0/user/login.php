<?php
if (DeviceIsExistByClientId($args['client_id'])) {
  $responce = UserLogin($args['email'], $args['password'], $args['eval']);
  if ($responce['uid']) {
    DeviceClientIdSetPermission($args['client_id'], 'allow');
    DeviceClientIdSetUid($args['client_id'], $responce['uid']);
    unset($responce['uid']);
  }
} else {
  $responce = errorHandler('AppRouterMethod.client_id_deprecated');
}