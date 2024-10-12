<?php
function AppRouterStatic ($version, $method, $args=[]) {
  global $config;

  $responce = [];
  $responce_code = 404;

  $fileFormat = '.json';
  $filename = DIR . '/core/static/' . $method . '.v' . $version . $fileFormat;

  if (file_exists($filename)) {
    $responce = json_decode(file_get_contents($filename, true));
    $responce_code = 200;
  } else {
    return errorHandler('AppRouterStatic.method_not_found');
  }
  // responce
  if ($responce) $config['responce']($responce, $responce_code);
}
