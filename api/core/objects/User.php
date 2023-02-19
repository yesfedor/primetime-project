<?php
// User Create
/**
 * @todo ХУЙНЯ С РЕГИСТРАЦИЕЙ - success
 */
function UserCreate (string $name, string $surname, string $gender, string $birthday, string $email, string $password) {
  // check name
  if (!UserValidatorName($name)) return UserHandler(1001);

  // check surname
  if (!UserValidatorSurname($surname)) return UserHandler(1002);

  // check birthday
  if (!UserValidatorBirthday($birthday)) return UserHandler(1003);

  if (!UserValidatorAgeOver13($birthday)) return UserHandler(1004);

  // check email
  if (!UserValidatorEmail($email)) return UserHandler(1008);

  // is the mail registered?
  if (UserIsExistByEmail($email)) return UserHandler(1007);

  // check password
  if (!UserValidatorPassword($password)) return UserHandler(1009);

  $passwordOriginal = $password;
  $password = UserGeneratorPassword($password);

  // check gender
  $gender = UserValidatorGender($gender);

  // created
  $dateNow = time();

  $query = "INSERT INTO `User` (`uid`, `domain`, `password`, `name`, `surname`, `dateRegistration`, `dateVisit`, `gender`, `birthday`, `avatar`, `phone`, `email`, `access`, `blocking`) 
            VALUES (NULL, NULL, :password, :name, :surname, :dateRegistration, :dateVisit, :gender, :birthday, NULL, NULL, :email, :access, 'none')";
  $var = [
    ':password' => $password,
    ':name' => $name,
    ':surname' => $surname,
    ':dateRegistration' => $dateNow,
    ':dateVisit' => $dateNow,
    ':gender' => $gender,
    ':birthday' => $birthday,
    ':email' => $email,
    ':access' => 'user-default'
  ];

  $uid = dbAddOne($query, $var);

  return UserLogin($email, $passwordOriginal);
}

// User Login
function UserLoginAuthorizeIt (array $user, bool $eval=false) {
  // everything is fine, we authorize it
  $userUnique = $user['uid'];
  $userAccess = $user['access'];
  $userAvailableProperties = [
    'uid' => $user['uid'],
    'domain' => $user['domain'],
    'telegram' => $user['telegram'] || false,
    'uid_crypt' => $user['telegram'] || false,
    'password' => $user['password'],
    'name' => $user['name'],
    'surname' => $user['surname'],
    'gender' => $user['gender'],
    'birthday' => $user['birthday'],
    'avatar' => $user['avatar'],
    'email' => $user['email'],
    'access' => $user['access'],
    'blocking' => $user['blocking']
  ];
  
  $jwt = UserJwtEncode($userUnique, $userAvailableProperties, $userAccess);
  $loginData['jwt'] = $jwt;
  $loginData['uid'] = $user['uid'];

  if ($eval) {
    $jwtPayload = explode('.', $jwt)[1];
    $loginData['eval'] = "(function () {return JSON.parse(atob('$jwtPayload'))}());";
  }

  return $loginData;
}

function UserLogin (string $loginInput, string $password, bool $eval=false) {
  // looking for an account
  $user = UserGetByLoginInput($loginInput);
  // checking if it exists
  if (!$user['uid']) return UserHandler(1006);

  // checking the password
  $passwordSalt = UserGeneratorPassword($password);
  if ($passwordSalt !== $user['password']) return UserHandler(1010);

  $loginData = UserLoginAuthorizeIt($user, $eval);
  
  return $loginData;
}

// User Read
/**
 * @method UserGetByUid
 */
function UserRefreshJwt (string $jwt, string $client_id='') {
  // fetch user
  if (!UserJwtIsValid($jwt)) return UserHandler(1011);
  $user = UserJwtDecode($jwt)['data'];
  $user_db = UserGetByUid($user['uid']);

  // check password
  if ($user_db['password'] !== $user['password']) return ['jwt' => 'LOGOUT'];
  $platform = DeviceGetLastPlatformByUid($user['uid']);
  UserUidSetOnline($user['uid'], $platform);

  // generated new jwt
  $loginData = UserLoginAuthorizeIt($user_db, false);

  // get, set, update Device permission
  if ($client_id) {
    DeviceClientIdSetPermission($client_id, 'allow');
    $device = DeviceGetByClientId($client_id);
    $loginData['permission'] = $device['permission'];
  }

  return $loginData;
}

function UserCheckPasswordByJwt (string $jwt) {
  if (!UserJwtIsValid($jwt)) return UserHandler(1011);
  $user = UserJwtDecode($jwt)['data'];
  $user_db = UserGetByUid($user['uid']);

  // check password
  if ($user_db['password'] !== $user['password']) return false;

  return true;
}

function UserGetByUid (int $uid) {
  $query = "SELECT * FROM User WHERE uid = :uid and blocking != :blocking";
  $var = [
    ':uid' => $uid,
    ':blocking' => 'deleted'
  ];
  $user = dbGetOne($query, $var);

  return $user;
}

function UserGetByEmail (string $email) {
  $query = "SELECT * FROM User WHERE email = :email and blocking != :blocking";
  $var = [
    ':email' => $email,
    ':blocking' => 'deleted'
  ];
  $user = dbGetOne($query, $var);

  return $user;
}

function UserGetByDomain (string $domain) {
  $query = "SELECT * FROM User WHERE domain = :domain and blocking != :blocking";
  $var = [
    ':domain' => $domain,
    ':blocking' => 'deleted'
  ];
  $user = dbGetOne($query, $var);

  return $user;
}

function UserGetByLoginInput (string $input) {
  $query = "SELECT * FROM User WHERE email = :input or domain = :input or phone = :input and blocking != :blocking";
  $var = [
    ':input' => $input,
    ':blocking' => 'deleted'
  ];
  $user = dbGetOne($query, $var);

  return $user;
}

function UserGetPublicByUid (int $uid = 0, string $domain = '') {
  if ($uid !== 0) {
    $query = "SELECT uid, domain, name, surname, dateRegistration, dateVisit, platformVisit, gender, avatar, blocking, status FROM User WHERE uid = :uid";
    $var = [
      ':uid' => $uid
    ];
    $user = dbGetOne($query, $var);
  }
  if ($domain !== '') {
    $query = "SELECT uid, domain, name, surname, dateRegistration, dateVisit, platformVisit, gender, avatar, blocking, status FROM User WHERE domain = :domain";
    $var = [
      ':domain' => $domain
    ];
    $user = dbGetOne($query, $var);
  }

  if (!$user['uid']) $user = ['uid' => 0];

  return $user;
}

/**
 * @method UserIsExistByUid
 * @return True|False
 */
function UserIsExistByUid (int $uid) {
  $user = UserGetByUid($uid);

  if ($user['uid']) return true;
  else return false;
}

function UserIsExistByEmail(string $email) {
  $user = UserGetByEmail($email);

  if ($user['email']) return true;
  else return false;
}

function UserSessionsShow (string $jwt) {
  $user = UserJwtDecode($jwt)['data'];
  $sessions = DeviceGetAllByUid($user['uid']);
  return $sessions;
}

function UserSessionsLogout(string $jwt, string $client_id, string $specified_client_id = '') {
  $user = UserJwtDecode($jwt)['data'];

  if ($specified_client_id !== '') {
    // getting out of one
    DeviceLogoutSpecified($user['uid'], $specified_client_id);

    $sessions = UserSessionsShow($jwt);
    return ['type' => 'specified', 'sessions' => $sessions];
  } else {
    // getting out of all
    DeviceLogoutAll($user['uid'], $client_id);

    $sessions = UserSessionsShow($jwt);
    return ['type' => 'all', 'sessions' => $sessions];
  }
}

// User Update
function UserEditSave (string $jwt, array $ctx=['name'=> '', 'surname' => '', 'birthday' => '', 'gender' => '']) {
  $user = UserJwtDecode($jwt);
  if (!$user['data']['uid'] || !UserCheckPasswordByJwt($jwt)) return UserHandler(1011);

  $name = $ctx['name'];
  $surname = $ctx['surname'];
  $birthday = $ctx['birthday'];
  $gender = $ctx['gender'];

  $payload = [
    ':uid' => $user['data']['uid']
  ];

  if ($name) {
    // check name
    if (!UserValidatorName($name)) return UserHandler(1001);
    $payload[':name'] = $name;
  }
  if ($surname) {
    // check surname
    if (!UserValidatorSurname($surname)) return UserHandler(1002);
    $payload[':surname'] = $surname;
  }
  if ($birthday) {
    // check birthday
    if (!UserValidatorBirthday($birthday)) return UserHandler(1003);
    if (!UserValidatorAgeOver13($birthday)) return UserHandler(1004);
    $payload[':birthday'] = $birthday;
  }
  if ($gender) {
    // check gender
    $payload[':gender'] = UserValidatorGender($gender);
  }

  $query_update = "UPDATE User SET"
    .($payload[':name'] ? ' name = :name' : '')
    .($payload[':surname'] ? ($payload[':name'] ? ',' : '').' surname = :surname' : '')
    .($payload[':birthday'] ? ($payload[':surname'] ? ',' : '').' birthday = :birthday' : '')
    .($payload[':gender'] ? ($payload[':birthday'] ? ',' : '').' gender = :gender' : '')
    ." WHERE uid = :uid";
  
  dbAddOne($query_update, $payload);

  return UserRefreshJwt($jwt);
}

function UserUpdateStatus (int $uid, string $status = '') {
  $query = "UPDATE User SET status = :status WHERE uid = :uid";
  $var = [
    ':uid' => $uid,
    ':status' => $status
  ];
  dbAddOne($query, $var);
}

function UserUidSetOnline (int $uid, string $platform) {
  $query = "UPDATE User SET dateVisit = :time, platformVisit = :platformVisit WHERE uid = :uid";
  $var = [
    ':uid' => $uid,
    ':platformVisit' => $platform,
    ':time' => time()
  ];
  dbAddOne($query, $var);
}

// User Delete

// User Handler []
function UserHandler ($responce_code, $responce=[]) {
  switch ($responce_code) {
    default:
      return 1000;
    case 1001:
      $responce_code = 1001;
      $responce = ['error' => 'enter your real name'];
      break;
    case 1002:
      $responce_code = 1002;
      $responce = ['error' => 'enter your real surname'];
      break;
    case 1003:
      $responce_code = 1003;
      $responce = ['error' => 'enter your real date of birth, you must be over 13 years old'];
      break;
    case 1004:
      $responce_code = 1004;
      $responce = ['error' => 'you must be over 13 years old'];
      break;
    case 1006:
      $responce_code = 1006;
      $responce = ['error' => 'the account with the specified email address was not found'];
      break;
    case 1007:
      $responce_code = 1007;
      $responce = ['error' => 'this email already has an account'];
      break;
    case 1008:
      $responce_code = 1008;
      $responce = ['error' => 'the specified email address is not valid'];
      break;
    case 1009:
      $responce_code = 1009;
      $responce = ['error' => 'enter a password that exceeds 7 characters'];
      break;
    case 1010:
      $responce_code = 1010;
      $responce = ['error' => 'invalid password'];
      break;
    case 1011:
      $responce_code = 1011;
      $responce = ['error' => 'JWT invalid token'];
    case 1012:
      $responce_code = 1012;
      $responce = ['error' => 'enter uid or domain args'];
  }

  return FinalHandler($responce_code, $responce);
}

// User Validator [True||False]
function UserValidatorName (string $name) {
  $regexp_name = '/^([А-Я]{1}[а-яё]{1,23}|[A-Z]{1}[a-z]{1,23})$/u';

  if (preg_match($regexp_name, $name)) return true;
  else return false;
}

function UserValidatorSurname (string $surname) {
  $regexp_surname = '/^([А-Я]{1}[а-яё]{1,23}|[A-Z]{1}[a-z]{1,23})$/u';
  if (preg_match($regexp_surname, $surname)) return true;
  else return false;
}

function UserValidatorBirthday (string $birthday) {
  $regexp_birthday = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
  if (preg_match($regexp_birthday, $birthday)) return true;
  else return false;
}

function UserValidatorAgeOver13 (string $birthday) {
  $birthday_year = (int) explode('-', $birthday)[0];
  if (date('Y') - $birthday_year > 13) return true;
  else return false;
}

function UserValidatorPassword (string $password) {
  if (mb_strlen($password) >= 8) return true;
  else return false;
}

function UserValidatorGender (string $gender) {
  $genders_allow = ['male', 'female'];
  if (in_array($gender, $genders_allow)) return $gender;
  else return $genders_allow[0];
}

function UserValidatorEmail (string $email) {
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;

  return true;
}

// User Generators
function UserGeneratorPassword (string $password) {
  return md5($password).md5(md5($password)).md5(md5($password).md5(md5($password)));
}

// JWT
// UserJwtEncode ($unique, $jwt)
function UserJwtEncode(int $unique=0, array $user, string $access='default') {
  $server_token = md5('lFg486982lFg');
  $user_token = md5($unique.'_key');

  $header = [
      'alg' => 'sha256',
      'typ' => 'JWT',
      'unique' => $unique
  ];

  $data = $user;
  $data['auth'] = true;

  $str1 = base64_encode(json_encode($header));
  $str2 = base64_encode(json_encode($data));
  $str3 = hash_hmac('sha256', md5($unique.json_encode($data).$user_token), $server_token);

  return $str1.'.'.$str2.'.'.$str3;
}

// UserJwtDecode ($jwt) : return user [$header, $data, $signature, $hash_hmac]
function UserJwtDecode(string $jwt='0.0.0') {
  $server_token = md5('lFg486982lFg');
  
  $jwt = explode('.', $jwt);
  $header = json_decode(base64_decode($jwt[0]), true);
  $data = json_decode(base64_decode($jwt[1]), true);
  $signature = $jwt[2];

  $unique = $header['unique'];
  $user_token = md5($header['unique'].'_key');
  $hash_hmac = hash_hmac('sha256', md5($unique.json_encode($data).$user_token), $server_token);

  return ['header' => $header, 'data' => $data, 'signature' => $signature, 'hash_hmac' => $hash_hmac];
}

// UserJwtIsValid ($jwt)
function UserJwtIsValid(string $jwt) {
  $jwt_content = UserJwtDecode($jwt);
  // time
      // check todo
  // signature
  if ($jwt_content['signature'] == $jwt_content['hash_hmac']) return true;
  else return false;
}