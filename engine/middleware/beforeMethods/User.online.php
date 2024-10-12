<?php
// init middleware
$abort = false;
$abortData = [];

// User.online middleware

// Device
if ($args['client_id']) {
  $device = DeviceGetByClientId ($args['client_id']);
  if ($device['client_id']) {
    if ($device['uid']) {
      DeviceClientIdSetUid($args['client_id'], $device['uid']);
      DeviceUidSetOnline($device['uid'], $device['platform']);
    }
  } else {
    $abort = true;
    $abortData = DeviceHandler(1001);
  }
}

// User
if ($args['jwt']) {
  if (UserJwtIsValid($args['jwt'])) {
    $user = UserJwtDecode($args['jwt'])['data'];
    $platform = DeviceGetLastPlatformByUid($user['uid']);
    UserUidSetOnline($user['uid'], $platform);
  } else {
    $abort = true;
    $abortData = UserHandler(1011);
  }
}

// Device and User
if ($device['id'] and $user['uid']) {
  DeviceClientIdSetUid($args['client_id'], $user['uid']);
}