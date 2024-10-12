<?php
// DataBase settings
$config['db'] = [
  'host' => 'db.iny.su:3333',
  'dbname' => 'prime',
  'user' => 'prime',
  'password' => 'hidden'
];

// Host setings
$config['hostnameApi'] = 'primetime.su';
$config['docsHostname'] = 'primetime.su';
$config['protocol'] = 'https://';

// App base link
$config['mainAppUrl'] = $config['protocol'] . $config['hostnameApi'];

// Docs base link
$config['docs'] = $config['protocol'] . $config['docsHostname'] . '/api/docs';

// Responce body function in handlers/Responce.php
$config['responce'] = 'ResponceWithJSON';
