<?php

/**
 * @param File image
 * @return String path
 */
function FileImageUpload($file) {
  if ($file['error'] !== 0) return ['code' => 500];
  $allowTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];

  $type = $file['type'];
  if (!in_array($type, $allowTypes, true)) return ['code' => 500];

  $filename = $file['name'];
  $size = $file['size'];
  if ($size > 8388608) return ['code' => 500];

  $tmp_name = $file['tmp_name'];

  $paths = FileGeneratorUploadPath('image', $filename, $size);

  move_uploaded_file($tmp_name, $paths['private']);

  return ['code' => 200, 'path' => $paths['public']];
}

function FileGeneratorUploadPath (string $type, string $filename, int $size) {
  global $config;

  $filename_format = array_pop(explode('.', $filename));
  $dir = '/uploaded-data';
  $prefix = $dir . '/';
  $postfix = '.'.$filename_format;

  switch ($type) {
    case 'image':
      $prefix .= 'uc/';
      break;
  }

  $gen = $prefix.FileGeneratorFilename($filename, $size).$postfix;

  $paths = [
    'public' => $config['mainAppUrl'].$gen,
    'private' => $_SERVER['DOCUMENT_ROOT'].$gen
  ];

  return $paths;
}

function FileGeneratorFilename (string $filename, int $size) {
  $a = md5($filename . '..−. .− .−−− .−..' . $size);
  $b = time();
  $c = $filename .= ' ..−. .− .−−− .−..';
  $d = $size .= ' ..−. .− .−−− .−..';
  $f = hash('sha256', '0'.$a.'0'.$b.'0'.$c.'0'.$d.'0');
  $name = substr($f, 0, 8);
  return $name;
}
?>