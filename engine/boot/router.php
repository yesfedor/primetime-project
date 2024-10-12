<?php
function AppRouter ($location, $initArgs) {
  global $config;

  // parse $location
  // $path
  $location = explode('?', $location);
  $path = explode('/', urldecode(substr($location[0], 1)));
  // $args
  if ($location[1]) parse_str($location[1], $args);
  else $args = [];
  $args = array_merge($initArgs, $args);

  // routing
  switch($path[0]) {
    case 'docs':
      if ($config['hostnameApi'] !== $config['docsHostname']) {
        if ($_SERVER['HTTP_HOST'] === $config['hostnameApi']) {
          header("Location: {$config['docs']}");
          return;
        }
      }

      break;
    case 'method':
      if ($config['hostnameApi'] !== $config['docsHostname']) {
        if ($_SERVER['HTTP_HOST'] === $config['docsHostname'] && !$path[1]) {
          header("Location: {$config['docs']}");
          return;
        }
      }

      require_once(DIR .  '/core/boot/routers/AppRouterStatic.php');
      require_once(DIR . '/core/boot/routers/AppRouterMethod.php');
      AppRouterMethod($args['v'], $path[1], $args);
      break;
    case 'static':
      require_once(DIR .  '/core/boot/routers/AppRouterStatic.php');
      AppRouterStatic($args['v'], $path[1], $args);
      break;
    default:
      if ($_SERVER['HTTP_HOST'] === $config['hostnameApi']) header("Location: {$config['mainAppUrl']}");
      if ($_SERVER['HTTP_HOST'] === $config['docsHostname']) header("Location: {$config['docs']}");
      return;
  }
}