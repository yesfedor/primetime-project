<?php
// Create client_id
function DeviceCreateClientId (int $app_id, string $platform) {
  $ip = DeviceGetIp();

  if (!DeviceValidatorPlatform($platform)) return DeviceHandler(1002);

  $app = ApplicationsGetByAppId($app_id);
  if (!$app['app_id']) return DeviceHandler(1001);

  $client_id = DeviceGeneratorClientId();

  $query = "INSERT INTO `Devices` (`id`, `client_id`, `app_id`, `platform`, `ip`, `uid`, `time`) VALUES (NULL, :client_id, :app_id, :platform, :ip, NULL, :time)";
  $var = [
    ':client_id' => $client_id,
    ':app_id' => $app_id,
    ':platform' => $platform,
    ':ip' => $ip,
    ':time' => time()
  ];
  dbAddOne($query, $var);

  return (array) ['clientId' => $client_id];
}

// Update
function DeviceClientIdSetUid (string $client_id, int $uid) {
  $query = "UPDATE Devices SET uid = :uid, time = :timeSet WHERE client_id = :client_id";
  $var = [
    ':client_id' => $client_id,
    ':uid' => $uid,
    ':timeSet' => time()
  ];
  dbAddOne($query, $var); 
}

function DeviceUidSetOnline (int $uid, string $platform) {
  $query = "UPDATE User SET dateVisit = :dateVisit, platformVisit = :platformVisit WHERE uid = :uid";
  $var = [
    ':uid' => $uid,
    ':platformVisit' => $platform,
    ':dateVisit' => time()
  ];
  dbAddOne($query, $var);
}

function DeviceClientIdSetPermission (string $client_id, string $permission) {
  if ($permission !== 'allow' && $permission !== 'deny') die('Device: DevicePermissionSet');
  
  $query = "UPDATE Devices SET permission = :permission WHERE client_id = :client_id";
  $var = [
    ':client_id' => $client_id,
    ':permission' => $permission
  ];
  
  dbAddOne($query, $var);
}

function DeviceLogoutSpecified (int $uid, string $specified_client_id) {
  $session = DeviceGetByClientId($specified_client_id);

  if ($uid == $session['uid']) {
    DeviceClientIdSetPermission($specified_client_id, 'deny');
  }
}

function DeviceLogoutAll (int $uid, string $client_id) {
  $sessions = DeviceGetAllByUid($uid);
  
  for ($i = 0; $i < count($sessions); $i++) {
    if ($client_id === $sessions[$i]['client_id'] or $sessions[$i]['permission'] === 'deny') continue;
    DeviceClientIdSetPermission($sessions[$i]['client_id'], 'deny');
  }
}

// Getters
function DeviceGetIp() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
      $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function DeviceGetByClientId (string $client_id) {
  $query = "SELECT * FROM Devices WHERE client_id = :client_id";
  $var = [
    ':client_id' => $client_id
  ];
  $device = dbGetOne($query, $var);
  return $device;
}

function DeviceIsExistByClientId (string $client_id) {
  $device = DeviceGetByClientId($client_id);
  if ($device['id']) return true;
  else return false;
}

function DeviceGetAllByUid (int $uid) {
  $query = "SELECT * FROM Devices WHERE app_id = :app_id and uid = :uid and time > :time ORDER BY time DESC";
  $var = [
    ':app_id' => 1,
    ':uid' => $uid,
    ':time' => time() - 365 * 24 * 60 * 60 
  ];

  $sessions = dbGetAll($query, $var);
  return $sessions;
}

function DeviceGetLastPlatformByUid (int $uid) {
  $query = "SELECT platform FROM Devices WHERE uid = :uid ORDER BY time DESC LIMIT 1";
  $var = [
    ':uid' => $uid
  ];
  $device = dbGetOne($query, $var);
  if (!$device['platform']) return 'Undefined';
  $platform = $device['platform'];
  return $platform;
}

// Device Handler []
function DeviceHandler (int $responce_code, $responce=[]) {  
  switch ($responce_code) {
    default:
      $responce_code = 1000;
      $responce = [];
      break;
    case 1001:
      // 1001: app not found
      $responce_code = 1001;
      $responce = ['error' => 'app not found'];
      break;
    case 1002:
      // 1002: app not found
      $responce_code = 1002;
      $responce = ['error' => 'platform not found'];
      break;
    case 200:
      $responce_code = 200;
      break;
  }

  return FinalHandler($responce_code, $responce);
}

// Device Validator [True||False]
function DeviceValidatorPlatform (string $platform) {
  $userDevices = ['Android', 'iPhone', 'iPad', 'Symbian', 'Windows Phone', 'Tablet OS', 'Linux', 'Windows', 'Macintosh', 'Undefined'];
  if (in_array($platform, $userDevices)) return true;
  else return false;
}

// Generators
function DeviceGeneratorClientId () {
  $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}