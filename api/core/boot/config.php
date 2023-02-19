<?php
// DataBase settings
$config['db'] = [
  'host' => 'localhost',
  'dbname' => 'cc38255_starter',
  'user' => 'cc38255_starter',
  'password' => 'P36taBf1N8Rt9GF6'
];

// Host setings
$config['hostnameApi'] = 'iny.su';
$config['docsHostname'] = 'iny.su';
$config['protocol'] = 'https://';

// App base link
$config['mainAppUrl'] = $config['protocol'] . $config['hostnameApi'];

// Docs base link
$config['docs'] = $config['protocol'] . $config['docsHostname'] . '/api/docs';

// Responce body function in handlers/Responce.php
$config['responce'] = 'ResponceWithJSON';
