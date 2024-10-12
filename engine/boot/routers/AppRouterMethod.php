<?php
function AppRouterMethod ($version, $method, $args=[]) {
  global $config;
  
  // file bad html
  $fileVeryBad = 'Привет, раз ты видишь это, то <a href="tg://resolve?domain=yesfedor">разработчик</a> писавший этот метод ошибся в файле, напиши ему и прикрепи ссылочку, если не сложно)';

  // check version
  $versionsData = include DIR . '/core/methods/versions.php';
  if (!in_array($version, $versionsData, true)) return errorHandler('AppRouterMethod.version_not_found');

  // methodsData in methods.php defined version
  $methodsData = include DIR . '/core/methods/' . $version . '/methods.php';

  // method info in methods.php defined version
  $methodInfo = $methodsData[$method];
  
  // check method on avaible
  if (!$methodInfo['in_active']) return errorHandler('AppRouterMethod.method_not_found');

  // check included props
  $props = $methodInfo['props'];

  if (count($props) > 0) {
    foreach($props as $prop => $data) {
      if ($data['default'] and !$args[$prop] and !$data['required']) {
        $args[$prop] = $data['default'];
      }

      if (!$data['required']) {
        // the argument exists, leading to the type
        settype($args[$prop], $data['type']);
        continue;
      }

      if ($args[$prop]) {
        // the argument exists, leading to the type
        settype($args[$prop], $data['type']);
      } else {
        // the argument does not exist, we output an error
        $methodInfo['props'] = [$prop => $data];
        return errorHandler('AppRouterMethod.method_required_args', $methodInfo);
      }
    }
  }

  // prepare method
  $methodPath = str_replace('.', '/', $method);
  $methodPath = DIR . '/core/methods/' . $version . '/' . $methodPath . '.php';

  $responce = [];
  $responce_code = 200;
  
  // The method is not available on the server
  if (!file_exists($methodPath)) return errorHandler('AppRouterMethod.method_file_unavailable', $methodInfo['link']);

  // require objects
  if (count($methodInfo['objects']) > 0) {
    try {
      foreach($methodInfo['objects'] as $object) {
        // objects path
        $objectsPath = DIR . '/core/objects/' . $object . '.php';
        if (file_exists($objectsPath)) require_once($objectsPath);
        else return errorHandler('AppRouterMethod.method_file_unavailable', $methodInfo['link']);
      }
    } catch (Throwable $e) {
      if ($args['api'] == 'test') debug($e->getMessage());
      else {
        echo $fileVeryBad;
        return;
      }
    }
  }

  // require middleware beforeMethods
  if (count($methodInfo['beforeMethods']) > 0) {
    foreach($methodInfo['beforeMethods'] as $beforeMethod) {
      // beforeMethod path
      $beforeMethodPath = DIR . '/core/middleware/beforeMethods/' . $beforeMethod . '.php';
      if (file_exists($beforeMethodPath)) {
        // Abort with beforeMethods
        $abort = false;

        // return error reason
        $abortData = [];

        require_once($beforeMethodPath);

        $methodInfo['abortData'] = $abortData;
        if ($abort) return errorHandler('AppRouterMethod.before_handler_error', $methodInfo);
      }
      else return errorHandler('AppRouterMethod.method_file_unavailable', $methodInfo['link']);
    }
  }

  // require method
  try {
    require_once($methodPath);
  } catch (Throwable $e) {
    if ($args['api'] == 'test') debug($e->getMessage());
    else {
      echo $fileVeryBad;
      return;
    }
  }

  // responce
  if ($responce) $config['responce']($responce, $responce_code);
}