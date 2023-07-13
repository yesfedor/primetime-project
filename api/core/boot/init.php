<?php
function cors() {
  // Allow from any origin
  if (isset($_SERVER['HTTP_ORIGIN'])) {
      // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
      // you want to allow, and if so:
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Max-Age: 86400');    // cache for 1 day
  }
  // Access-Control headers are received during OPTIONS requests
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
          // may also be using PUT, PATCH, HEAD etc
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  
      exit(0);
  }
}

cors();

if ($_GET['api'] == 'test') {
  ini_set('error_reporting', E_ALL); 
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
}

function debug ($bug) {
  echo '<pre>';
  print_r($bug);
  echo '</pre>';
}

function ExceptionJsonExtreme ($moduleName, Exception $e) {
  if ($_GET['api'] == 'test') {
    echo $moduleName;
    echo $e->getMessage();
  } else {
    echo json_encode(['error' => 1, 'moduleName' => $moduleName, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
  }
}

try {
  require_once(DIR . '/core/boot/config.php');
} catch (Exception $e) {
  ExceptionJsonExtreme('config', $e);
}

try {
  require_once(DIR . '/core/handlers/Error.php');
} catch (Exception $e) {
  ExceptionJsonExtreme('Error', $e);
}

try {
  require_once(DIR . '/core/handlers/Responce.php');
} catch (Exception $e) {
  ExceptionJsonExtreme('Responce', $e);
}

try {
  require_once(DIR . '/core/boot/db.php');
} catch (Exception $e) {
  ExceptionJsonExtreme('db', $e);
}

try {
  require_once(DIR . '/core/boot/router.php');
} catch (Exception $e) {
  ExceptionJsonExtreme('router', $e);
}

// AppRouter
// setting the main directory - /api
$appUrl = str_replace('/api', '',$_SERVER['REQUEST_URI']);

if (file_get_contents('php://input')) {
  if (!empty($_POST)) {
    $_POST = array_merge(json_decode(file_get_contents('php://input'), true), $_POST);
  } else {
    $_POST = json_decode(file_get_contents('php://input'), true);
  }
}

try {
  ExceptionJsonExtreme('AppRouter', $_POST);
  AppRouter($appUrl, $_POST);
} catch (Exception $e) {
  ExceptionJsonExtreme('AppRouter', $e);
}
