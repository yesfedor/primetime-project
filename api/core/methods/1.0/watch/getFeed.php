<?php
global $config;
require_once(DIR . '/core/objects/User.php');

function getTimeForCard($value)
{
  $d = DateTime::createFromFormat('Y-m-d H:i:s', $value);
  if ($d === false) {
    return time();
  } else {
    return $d->getTimestamp();
  }
}

function findOutTheInnerSerialId($kpid)
{
  $apiToken = 'dhYHeqruV5o7oint21ggMMIUBaDE0Rm6';
  $api = "https://videocdn.tv/api/short?kinopoisk_id=$kpid&api_token=$apiToken";
  $data = json_decode(file_get_contents($api), true);
  if ($data['data'][0]['id']) {
    return $data['data'][0]['id'];
  } else return false;
}

function getFeedDataByKpid($videocdnSerialId)
{
  $apiLimit = 10;
  $apiToken = 'dhYHeqruV5o7oint21ggMMIUBaDE0Rm6';
  $api = "https://videocdn.tv/api/tv-series/episodes?tv_series_id=$videocdnSerialId&ordering=ru_released&direction=desc&limit=$apiLimit&page=1&api_token=$apiToken";
  $data = json_decode(file_get_contents($api), true);
  return $data;
}

function generateFeedByKpid($kpid)
{
  $videocdnSerialId = findOutTheInnerSerialId($kpid);
  if ($videocdnSerialId) {
    $episodesData = getFeedDataByKpid($videocdnSerialId);
    if ($episodesData['result'] <= 0) return false;

    $episodes = [];

    foreach ($episodesData['data'] as $key => $value) {
      $episodes[] = [
        'episode' => intval($value['num']),
        'season' => intval($value['season_num']),
        'kinopoiskId' => intval($value['kinopoisk_id']),
        'nameRu' => $value['ru_title'] ? $value['ru_title'] : $value['orig_title'],
        'time' => getTimeForCard($value['created'])
      ];
    }
    // inclide Array items sort by episodes
    return $episodes;
  } else {
    return false;
  }
}

function feedContentGenerate($feedData, $kpidList) {
  foreach ($kpidList as $kpid) {
    $feedByKpid = generateFeedByKpid($kpid, $feedData['binding'][$kpid]['year'], $feedData['binding'][$kpid]['nameRu']);
    if ($feedByKpid !== false) {
      $feedData['content'] = [...$feedData['content'], ...$feedByKpid];
    }
  }

  return $feedData;
}

function _WatchGetSubscriptions (string $jwt, int $uid = 0) {
  if ($jwt === '807a4af6') {
    $user['uid'] = $uid;
  } else {
    if (!UserJwtIsValid($jwt)) return ['code' => 404];
    $user = UserJwtDecode($jwt)['data'];
  }

  if (!$user['uid']) return ['code' => 404];

  $query_subscriptions = "SELECT id, kinopoiskId FROM WatchSubscribe WHERE uid = :uid and status = :status ORDER BY time DESC";
  $var_subscriptions = [
    ':uid' => $user['uid'],
    ':status' => 'subscribe'
  ];

  $subscriptionsData = dbGetAll($query_subscriptions, $var_subscriptions);
  if (!count($subscriptionsData) > 0) {
    return [
      'code' => 404,
      'total' => 0,
      'content' => []
    ];
  }


  $kinopoiskIdList = [];
  foreach ($subscriptionsData as $key => $value) {
    $kinopoiskIdList[] = $value['kinopoiskId'];
  }
  $kinopoiskIdList = implode(',', $kinopoiskIdList);

  $query_content = "SELECT id, kinopoiskId, nameRu, ratingAgeLimits, ratingKinopoisk, posterUrl, type, year FROM WatchContent WHERE kinopoiskId IN ($kinopoiskIdList) and kinopoiskId != :kinopoiskId ORDER BY FIELD(kinopoiskId, $kinopoiskIdList)";
  $var_content = [
    ':kinopoiskId' => 0
  ];
  $content = dbGetAll($query_content, $var_content);

  return [
    'code' => 200,
    'total' => count($subscriptionsData),
    'content' => $content
  ];
}

$subscriptionsData = [
  'total' => 0
];

if ($args['jwt'] && $args['client_id'] && $args['jwt'] !== 'system' && $args['client_id'] !== 'system') {
  $subscriptionsData = _WatchGetSubscriptions($args['jwt'], 0);
}

if ($args['system'] === '807a4af6' && $args['uid'] > 0) {
  $subscriptionsData = _WatchGetSubscriptions('807a4af6', $args['uid']);
}

$kpidList = [];
$feedData = [
  'code' => 404,
  'total' => 0,
  'content' => [],
  'binding' => []
];

/* IF !$subscriptionsData['content'] */
if ($subscriptionsData['total'] <= 0) return $feedData;

foreach ($subscriptionsData['content'] as $key => $value) {
  if ($value['type'] == 'TV_SERIES') {
    if (!in_array($value['kinopoiskId'], $kpidList)) {
      $kpidList[] = $value['kinopoiskId'];
      $feedData['binding'][$value['kinopoiskId']] = $value;
    }
  }
}

if (count($kpidList) <= 0) return $feedData;
$kpidListString = implode(',', $kpidList);

$query_check = "SELECT * FROM WatchFeedCache WHERE kpidList = :kpidList";
$var_check = [
  ':kpidList' => $kpidListString
];
$select_check = dbGetOne($query_check, $var_check);

$timeOffset = 6 * 60 * 60 - 1;
$currentTime = time();
// проверяем есть ли $kpidListString в базе
// echo 'act: init' . PHP_EOL;
if ($select_check['id']) {
  // echo 'act: if select_check' . PHP_EOL;
  
  // если есть - проверяем time на промежуток в 12 часов
  if (intval($select_check['time']) + $timeOffset < $currentTime) {
    // echo 'act: если 6 часов прошло - обновляем' . PHP_EOL;
    // - если 6 часов прошло - обновляем
    // - $feedByKpid = generateFeedByKpid($kpid, $feedData['binding'][$kpid]['year'], $feedData['binding'][$kpid]['nameRu']);
    // - после генерации обновляем запись
    $feedData = feedContentGenerate($feedData, $kpidList);
    $query_update = "UPDATE `WatchFeedCache` SET data = :data, time = :time WHERE id = :id";
    $var_update = [
      ':id' => $select_check['id'],
      ':data' => json_encode($feedData, JSON_UNESCAPED_UNICODE),
      ':time' => $currentTime,
    ];
    dbAddOne($query_update, $var_update);
  } else {
    // echo 'act: если 6 часов не прошло - выдаем data' . PHP_EOL;
    // - если 6 часов не прошло - выдаем data
    $feedData = json_decode($select_check['data'], true);
  }
} else {
  // echo 'act: else select_check' . PHP_EOL;
  // если нет - генерируем
  // $feedByKpid = generateFeedByKpid($kpid, $feedData['binding'][$kpid]['year'], $feedData['binding'][$kpid]['nameRu']);
  // после генерации создаем запись
  $feedData = feedContentGenerate($feedData, $kpidList);
  $query_insert = "INSERT INTO `WatchFeedCache`(`id`, `data`, `kpidList`, `time`) VALUES (NULL, :data, :kpidList, :time)";
  $var_insert = [
    ':data' => json_encode($feedData, JSON_UNESCAPED_UNICODE),
    ':kpidList' => $kpidListString,
    ':time' => $currentTime
  ];
  dbAddOne($query_insert, $var_insert);
}

// foreach been here

$feedDataContentCount = count($feedData['content']);

if ($feedDataContentCount <= 0) return $feedData;

$feedData['code'] = 200;
$feedData['total'] = $feedDataContentCount;

if (!$args['silent']) $responce = $feedData;
else $responce['code'] = 'pending';
