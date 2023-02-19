<?php
// Applications Create
/**
 * @method ApplicationsCreate
 * @return True|False - is created
 */
function ApplicationsCreate (string $title, int $owner_uid, string $redirect_uri='/') {
  // check title
  if (!ApplicationValidatorAppTitle($title)) return ApplicationsHandler(1001);

  // check owner_uid
  if (!UserIsExistByUid($owner_uid)) return ApplicationsHandler(1002);

  // check redirect_uri
  if (!ApplicationValidatorAppRedirectUri($redirect_uri)) return ApplicationsHandler(1003);

  $domain = parse_url($redirect_uri)['host'];
  $redirect_uri = parse_url($redirect_uri)['path'];


  $secure_key = ApplicationsGeneratorSecureKey($owner_uid, 'secure_key_hash:'.$title);
  $access_key = ApplicationsGeneratorAccessKey($owner_uid, 'access_key_hash:'.$title);

  $query = "INSERT INTO `Applications` (`app_id`, `title`, `is_verified`, `owner_uid`, `secure_key`, `access_key`, `status`, `access_permission`, `domain`, `redirect_uri`, `dateRegistration`) 
            VALUES (NULL, :title, '0', :owner_uid, :secure_key, :access_key, 'disabled', '[]', :domain, :redirect_uri, :dateRegistration)";
  $var = [
    ':title' => $title,
    ':owner_uid' => $owner_uid,
    ':secure_key' => $secure_key,
    ':access_key' => $access_key,
    ':domain' => $domain,
    ':redirect_uri' => $redirect_uri,
    ':dateRegistration' => time()
  ];

  $app_id = dbAddOne($query, $var);

  $app = ApplicationsGetByAppId($app_id);
  
  return $app;
}

// Applications Read
function ApplicationsGetByAppId (int $app_id) {
  $query = "SELECT * FROM Applications WHERE app_id = :app_id and status = :status";
  $var = [
    ':app_id' => $app_id,
    ':status' => 'enabled'
  ];
  
  $app = dbGetOne($query, $var);
  return $app;
}

function AppWidgetAuth (int $app_id) {
  $app = ApplicationsGetByAppId($app_id);
  if ($app['app_id']) return [
    'app_id' => $app['app_id'],
    'title' => $app['title'],
    'is_verified' => $app['is_verified'],
    'access_permission' => $app['access_permission'],
    'domain' => $app['domain'],
    'redirect_uri' => $app['redirect_uri']
  ];
  else return ApplicationsHandler(1004);
}

function ApplicationIsExistByAppId (int $app_id) {
  $app = ApplicationsGetByAppId ($app_id);
  if ($app['owner_uid']) return true;
  else return false;
}

// Applications Update

// Applications Delete

// Applications Handler []
function ApplicationsHandler (int $responce_code, $responce=[]) {  
  switch ($responce_code) {
    default:
      $responce_code = 1000;
      $responce=[];
      break;
    case 1001:
      // 1001: error in the app name
      $responce_code = 1001;
      $responce = ['error' => 'error in the app name'];
      break;
    case 1002:
      // 1002: the user does not exist
      $responce_code = 1002;
      $responce = ['error' => 'the user does not exist'];
      break;
    case 1003:
      // 1003: invalid trusted url
      $responce_code = 1003;
      $responce = ['error' => 'invalid trusted url'];
      break;
    case 1004:
      // 1004: the specified application was not found
      $responce_code = 1004;
      $responce = ['error' => 'the specified application was not found'];
      break;
    case 200:
      $responce_code = 200;
      break;
  }

  return FinalHandler($responce_code, $responce);
}

// Applications Validator [True||False]
function ApplicationValidatorAppTitle (string $app_title) {
  $regexp_app_title = '/^[a-z0-9-_.,]+$/i';

  if (preg_match($regexp_app_title, $app_title)) return true;
  else return false;
}

function ApplicationValidatorAppRedirectUri (string $redirect_uri) {
  if (filter_var($redirect_uri, FILTER_VALIDATE_URL)) return true;
  else return false;
}

// Applications Generators
function ApplicationsGeneratorSecureKey (int $uid, string $hash='') {
  $serverHash = md5('I want to create a perfect world');
  $serverTime = md5(time() + 10);

  $h0 = md5($uid.$serverHash.$serverTime.$hash);
  
  $h = uniqid($h0 . '.');

  return $h;
}

function ApplicationsGeneratorAccessKey (int $uid, string $hash='') {
  $serverHash = md5('I want a world without wars and theft');
  $serverTime = md5(time() - 10);

  $h0 = md5($uid.$serverHash.$serverTime.$hash);
  
  $h = uniqid($h0 . '.');

  return $h;
}