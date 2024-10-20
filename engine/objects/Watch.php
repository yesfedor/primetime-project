<?php
require_once(DIR . '/core/helpers/Slug.php');

function WatchAddContentByData (array $contentPDOFromat) {
  if (!$contentPDOFromat[':kinopoiskId']) return false;
  $query = "INSERT INTO WatchContent (`id`, `slug`, `kinopoiskId`, `imdbId`, `nameRu`, `nameEn`, `posterUrl`, `posterUrlPreview`, `ratingKinopoisk`,
            `ratingKinopoiskVoteCount`, `ratingFilmCritics`, `ratingFilmCriticsVoteCount`, `year`, `filmLength`, `slogan`, `description`,
            `shortDescription`, `type`, `ratingAgeLimits`, `startYear`, `endYear`, `countries`, `genres`) VALUES (NULL, :slug, :kinopoiskId,
            :imdbId, :nameRu, :nameEn, :posterUrl, :posterUrlPreview, :ratingKinopoisk, :ratingKinopoiskVoteCount, :ratingFilmCritics,
            :ratingFilmCriticsVoteCount, :year, :filmLength, :slogan, :description, :shortDescription, :type, :ratingAgeLimits,
            :startYear, :endYear, :countries, :genres)";

  return dbAddOne($query, $contentPDOFromat);
}

function WatchGetDataFrom_KinopoiskApiUnofficial_ByKpid (int $kpid) {
  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');

  curl_setopt($ch, CURLOPT_URL, 'https://kinopoiskapiunofficial.tech/api/v2.2/films/'.$kpid); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);
  $content = json_decode($data, true);

  return $content;
}

function WatchAddDbIfExitsByKpid (int $kpid) {
  $query = "SELECT * FROM WatchContent WHERE kinopoiskId = :kinopoiskId";
  $var = [
    ':kinopoiskId' => $kpid
  ];

  $content = dbGetOne($query, $var);

  if ($content['kinopoiskId']) return true;

  $kinopoiskApiUnofficialData = WatchGetDataFrom_KinopoiskApiUnofficial_ByKpid($kpid);

  if(!$kinopoiskApiUnofficialData['kinopoiskId']) return false;

  // Format Data
  $allowType = ['FILM', 'VIDEO', 'TV_SERIES', 'MINI_SERIES', 'TV_SHOW'];
  if (in_array($kinopoiskApiUnofficialData['type'], $allowType)) $type = $kinopoiskApiUnofficialData['type'];
  else $type = $allowType[0];

  $ratingAgeLimits = str_replace('age', '', $kinopoiskApiUnofficialData['ratingAgeLimits']);

  $countries = [];
  foreach ($kinopoiskApiUnofficialData['countries'] as $key => $value) {
    $countries[] = $value['country'];
  }
  $countries = implode(',', $countries);

  $genres = [];
  foreach ($kinopoiskApiUnofficialData['genres'] as $key => $value) {
    $genres[] = $value['genre'];
  }
  $genres = implode(',', $genres);

  $nameForSlug = $kinopoiskApiUnofficialData['nameRu'] ? $kinopoiskApiUnofficialData['nameRu'] : $kinopoiskApiUnofficialData['nameEn'];
  if (mb_strlen($nameForSlug)) {
    $prefix = mb_substr($kinopoiskApiUnofficialData['kinopoiskId'], 0, 3) . '-';
    $slug = SlugCreateByText($prefix . $nameForSlug);
  }

  $contentPDOFromat = [
    ':slug' => $slug,
    ':kinopoiskId' => ($kinopoiskApiUnofficialData['kinopoiskId'] ? $kinopoiskApiUnofficialData['kinopoiskId'] : 0),
    ':imdbId' => ($kinopoiskApiUnofficialData['imdbId'] ? $kinopoiskApiUnofficialData['imdbId'] : 'tt0'),
    ':nameRu' => ($kinopoiskApiUnofficialData['nameRu'] ? $kinopoiskApiUnofficialData['nameRu'] : 'Empty'),
    ':nameEn' => ($kinopoiskApiUnofficialData['nameEn'] ? $kinopoiskApiUnofficialData['nameEn'] : 'Empty'),
    ':posterUrl' => ($kinopoiskApiUnofficialData['posterUrl'] ? $kinopoiskApiUnofficialData['posterUrl'] : ''),
    ':posterUrlPreview' => ($kinopoiskApiUnofficialData['posterUrlPreview'] ? $kinopoiskApiUnofficialData['posterUrlPreview'] : ''),
    ':ratingKinopoisk' => ($kinopoiskApiUnofficialData['ratingKinopoisk'] ? $kinopoiskApiUnofficialData['ratingKinopoisk'] : 0.0),
    ':ratingKinopoiskVoteCount' => ($kinopoiskApiUnofficialData['ratingKinopoiskVoteCount'] ? $kinopoiskApiUnofficialData['ratingKinopoiskVoteCount'] : 0),
    ':ratingFilmCritics' => ($kinopoiskApiUnofficialData['ratingFilmCritics'] ? $kinopoiskApiUnofficialData['ratingFilmCritics'] : 0.0),
    ':ratingFilmCriticsVoteCount' => ($kinopoiskApiUnofficialData['ratingFilmCriticsVoteCount'] ? $kinopoiskApiUnofficialData['ratingFilmCriticsVoteCount'] : 0),
    ':year' => ($kinopoiskApiUnofficialData['year'] ? $kinopoiskApiUnofficialData['year'] : 0),
    ':filmLength' => ($kinopoiskApiUnofficialData['filmLength'] ? $kinopoiskApiUnofficialData['filmLength'] : 0),
    ':slogan' => ($kinopoiskApiUnofficialData['slogan'] ? $kinopoiskApiUnofficialData['slogan'] : ''),
    ':description' => ($kinopoiskApiUnofficialData['description'] ? $kinopoiskApiUnofficialData['description'] : ''),
    ':shortDescription' => ($kinopoiskApiUnofficialData['shortDescription'] ? $kinopoiskApiUnofficialData['shortDescription'] : ''),
    ':type' => $type,
    ':ratingAgeLimits' => ($ratingAgeLimits ? $ratingAgeLimits : '16'),
    ':startYear' => ($kinopoiskApiUnofficialData['startYear'] ? $kinopoiskApiUnofficialData['startYear'] : 0),
    ':endYear' => ($kinopoiskApiUnofficialData['endYear'] ? $kinopoiskApiUnofficialData['endYear'] : 0),
    ':countries' => ($countries ? $countries : ''),
    ':genres' => ($genres ? $genres : '')
  ];

  return WatchAddContentByData($contentPDOFromat);
}

function WatchGetByKpid (int $kpid, string $jwt = '') {
  if ($jwt !== '') {
    if (!UserJwtIsValid($jwt)) return ['code' => 404];
    $user = UserJwtDecode($jwt)['data'];
  }

  WatchAddDbIfExitsByKpid($kpid);

  $query = "SELECT * FROM WatchContent WHERE kinopoiskId = :kinopoiskId";
  $var = [
    ':kinopoiskId' => $kpid
  ];

  if ($user and $user['uid'] and $user['access'] !== 'co-author') WatchHistoryAdd($kpid, $user['uid']);

  return dbGetOne($query, $var);
}

function WatchGetDataBySlug (string $slug, string $jwt = '') {
  if ($jwt !== '') {
      if (!UserJwtIsValid($jwt)) return ['code' => 404];
      $user = UserJwtDecode($jwt)['data'];
  }

  $query = "SELECT * FROM WatchContent WHERE slug = :slug";
  $var = [
      ':slug' => $slug
  ];

  $content = dbGetOne($query, $var);

  if (!$content['kinopoiskId']) {
    return null;
  }

  if ($user and $user['uid'] and $user['access'] !== 'co-author') WatchHistoryAdd($content['kinopoiskId'], $user['uid']);

  return $content;
}

function WatchGetKpidBySlug (string $slug) {
  $query = "SELECT * FROM WatchContent WHERE slug = :slug";
  $var = [
    ':slug' => $slug
  ];

  $content = dbGetOne($query, $var);

  if (!$content['kinopoiskId']) {
    return null;
  }

  return [
    'data' => $content
  ];
}

function WatchAddSimilarsByData (int $kpid, array $contentFromat) {
  if (!$kpid or !$contentFromat[0]) return false;

  foreach ($contentFromat as $key) {
    WatchAddDbIfExitsByKpid($key);
  }

  $kinopoiskIdList = implode(',', $contentFromat);

  $query = "INSERT INTO `WatchSimilars` (`id`, `kinopoiskId`, `kinopoiskIdList`, `time`) VALUES (NULL, :kinopoiskId, :kinopoiskIdList, :time)";
  $var = [
    ':kinopoiskId' => $kpid,
    ':kinopoiskIdList' => $kinopoiskIdList,
    ':time' => time()
  ];

  return dbAddOne($query, $var);
}

function WatchJoinSimilars ($sequelsAndPrequels, $similars) {
  if (!array_key_exists('total', $sequelsAndPrequels)) $sequelsAndPrequels = ['total' => 0, 'items' => []];
  if (!array_key_exists('total', $similars)) $similars = ['total' => 0, 'items' => []];
  if (!$sequelsAndPrequels['total'] and !$similars['total']) return ['total' => 0, 'items' => []];
  if (!$sequelsAndPrequels['total']) return $similars;
  if (!$similars['total']) return $sequelsAndPrequels;

  $content = [
    'total' => intval($sequelsAndPrequels['total']) + intval($similars['total']),
    'items' => array_merge($sequelsAndPrequels['total'], $similars['total'])
  ];

  return $content;
}

function WatchGetSimilarsFrom_KinopoiskApiUnofficial_ByKpid (int $kpid) {
  // similars
  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, 'https://kinopoiskapiunofficial.tech/api/v2.2/films/'.$kpid.'/similars'); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);
  $contentSimilars = json_decode($data, true);

  // sequels_and_prequels
  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, 'https://kinopoiskapiunofficial.tech/api/v2.1/films/'.$kpid.'/sequels_and_prequels'); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);
  $contentSequelsAndPrequels = json_decode($data, true);

  $content = WatchJoinSimilars($contentSequelsAndPrequels, $contentSimilars);
  return $content;
}

function WatchAddSimilarsDbIfExitsByKpid (int $kpid) {
  $query = "SELECT * FROM WatchSimilars WHERE kinopoiskId = :kinopoiskId";
  $var = [
    ':kinopoiskId' => $kpid
  ];
  $content = dbGetOne($query, $var);

  if ($content['time']) return true;

  $kinopoiskApiUnofficialData = WatchGetSimilarsFrom_KinopoiskApiUnofficial_ByKpid($kpid);

  if (!$kinopoiskApiUnofficialData['total']) return false;
  $kinopoiskIdList = [];
  foreach ($kinopoiskApiUnofficialData['items'] as $key => $value) {
    $kinopoiskIdList[] = $value['filmId'];
  }

  WatchAddSimilarsByData($kpid, $kinopoiskIdList);

  return $kinopoiskIdList;
}

function WatchGetSimilarsByKpid (int $kpid) {
  global $config;

  $isExits = WatchAddSimilarsDbIfExitsByKpid($kpid);

  $query_similars = "SELECT * FROM WatchSimilars WHERE kinopoiskId = :kinopoiskId";
  $var_similars = [
    ':kinopoiskId' => $kpid
  ];
  $similars = dbGetOne($query_similars, $var_similars);

  if (!$similars) {
    AppRouterStatic('1', 'getRecommendations', $args=[]);
    return false;
  }
  $kinopoiskIdList = $similars['kinopoiskIdList'];

  $query = "SELECT id, slug, kinopoiskId, nameRu, ratingAgeLimits, ratingKinopoisk, posterUrl, type, year FROM WatchContent WHERE kinopoiskId IN ($kinopoiskIdList) and kinopoiskId != :kinopoiskId";
  $var = [
    ':kinopoiskId' => $kpid
  ];

  $content['items'] = dbGetAll($query, $var);
  $content['total'] = count($content['items']);

  return $content;
}

function WatchUserRecord (int $kpid, string $jwt) {
  if (!UserJwtIsValid($jwt)) return ['status' => 'jwt_404'];
  $user = UserJwtDecode($jwt)['data']; // uid
  if (!$user['uid']) return ['status' => 'user_404'];

  $query = "SELECT * FROM WatchSubscribe WHERE uid = :uid and kinopoiskId = :kinopoiskId";
  $var = [
    ':uid' => $user['uid'],
    ':kinopoiskId' => $kpid
  ];
  $status = dbGetOne($query, $var);

  if (!$status['id']) return [
    'uid' => (int) $user['uid'],
    'kinopoiskId' => (int) $kpid,
    'status' => (string) 'unsubscribe',
    'time' => 0
  ];
  else return [
    'uid' => (int) $status['uid'],
    'kinopoiskId' => (int) $status['kinopoiskId'],
    'status' => (string) $status['status'],
    'time' => (int) $status['time']
  ];
}

function WatchSubscribeCreate ($uid, $kinopoiskId, $status) {
  $query = "INSERT INTO WatchSubscribe (`id`, `uid`, `kinopoiskId`, `status`, `time`) VALUES (NULL, :uid, :kinopoiskId, :status, :time)";
  $var = [
    ':uid' => $uid,
    ':kinopoiskId' => $kinopoiskId,
    ':status' => $status,
    ':time' => time()
  ];
  return dbAddOne($query, $var);
}

function WatchSubscribeUpdate ($uid, $kinopoiskId, $status) {
  $query = "UPDATE WatchSubscribe SET status = :status, time = :time WHERE uid = :uid and kinopoiskId = :kinopoiskId";
  $var = [
    ':uid' => $uid,
    ':kinopoiskId' => $kinopoiskId,
    ':status' => $status,
    ':time' => time()
  ];
  return dbAddOne($query, $var);
}

function WatchSubscribeManager (string $act, int $kpid, string $jwt) {
  if ($act !== 'subscribe' and $act !== 'unsubscribe') return ['status' => 'act_404'];
  if (!UserJwtIsValid($jwt)) return ['status' => 'jwt_404'];

  $user = UserJwtDecode($jwt)['data'];
  $watchUserRecord = WatchUserRecord($kpid, $jwt);

  if ($watchUserRecord['status'] === $act) return [
    'uid' => (int) $watchUserRecord['uid'],
    'kinopoiskId' => (int) $watchUserRecord['kinopoiskId'],
    'status' => (string) $watchUserRecord['status'],
    'time' => (int) $watchUserRecord['time']
  ];

  if ($watchUserRecord['time']) {
    WatchSubscribeUpdate($user['uid'], $kpid, $act);
    return WatchUserRecord($kpid, $jwt);
  } else {
    WatchSubscribeCreate($user['uid'], $kpid, $act);
    return WatchUserRecord($kpid, $jwt);
  }
}

function WatchFastSearchHistory (string $jwt) {
  if (!$jwt) return false;
  if (!UserJwtIsValid($jwt)) return false;
  $user = UserJwtDecode($jwt)['data'];
  if (!$user['uid']) return false;

  $query = "SELECT id, query FROM WatchSearchMetric WHERE uid = :uid AND is_deleted = :is_deleted ORDER BY time DESC LIMIT 10";
  $var = [
    ':uid' => $user['uid'],
    ':is_deleted' => '0'
  ];

  $result = dbGetAll($query, $var);

  $content = [
    'count' => count($result),
    'queries' => $result
  ];

  return $content;
}

function WatchFastSearchHistoryDelete (int $id, string $jwt) {
  if (!$jwt) return false;
  if (!UserJwtIsValid($jwt)) return false;
  $user = UserJwtDecode($jwt)['data'];
  if (!$user['uid']) return false;

  $query = "UPDATE WatchSearchMetric SET is_deleted = :is_deleted WHERE id = :id AND uid = :uid";
  $var = [
    ':id' => $id,
    ':uid' => $user['uid'],
    ':is_deleted' => '1'
  ];
  dbAddOne($query, $var);

  return ['status' => 'ok'];
}

function WatchFastSearchHistoryByKeyword (string $keyword, string $jwt = '') {
  // keyword first step in history
  $resultFirst = [];
  if ($jwt) {
    if (UserJwtIsValid($jwt)) {
      $user = UserJwtDecode($jwt)['data'];
      if ($user['uid']) {
        $queryFirst = "SELECT id, query FROM WatchSearchMetric WHERE uid = :uid AND is_deleted = :is_deleted AND query LIKE :keyword ORDER BY time DESC LIMIT 10";
        $varFirst = [
          ':uid' => $user['uid'],
          ':keyword' => '%' . $keyword . '%',
          ':is_deleted' => '0'
        ];
        $resultFirst = dbGetAll($queryFirst, $varFirst);
      }
    }
  }

  // keyword second step
  $resultSecond = WatchFastSearch($keyword, 15 - count($resultFirst));

  $content = [
    'count' => count($resultFirst) + $resultSecond['total'],
    'symlink' => $resultFirst,
    'watch' => $resultSecond['content'],
    'time' => time()
  ];

  return $content;
}

function WatchFastSearchAddMetric (string $searchQuery, string $jwt = '') {
  if (!$jwt) return false;
  if (!UserJwtIsValid($jwt)) return false;
  $user = UserJwtDecode($jwt)['data'];
  if (!$user['uid']) return false;
  if (!rawurldecode($searchQuery)) return false;
  if ($searchQuery === 'undefined') return false;

  $querySelect = "SELECT * FROM WatchSearchMetric WHERE uid = :uid AND query = :query AND is_deleted = :is_deleted";
  $varSelect = [
    ':uid' => $user['uid'],
    ':query' => rawurldecode($searchQuery),
    ':is_deleted' => '0'
  ];
  $select = dbGetOne($querySelect, $varSelect);
  if ($select['id']) {
    $queryUpdate = "UPDATE `WatchSearchMetric` SET time = :time WHERE id = :id";
    $varUpdate = [
      ':id' => $select['id'],
      ':time' => time()
    ];
    dbAddOne($queryUpdate, $varUpdate);

    return false;
  }

  $query = "INSERT INTO `WatchSearchMetric`(`id`, `uid`, `query`, `time`) VALUES (NULL, :uid , :query, :time)";
  $var = [
    ':uid' => $user['uid'],
    ':query' => rawurldecode($searchQuery),
    ':time' => time()
  ];

  dbAddOne($query, $var);
}

function WatchFastSearchPersonItems(string $query, int $limit = 3) {
  $query = urldecode($query);
  $query = rawurlencode($query);
  $url = 'https://kinopoiskapiunofficial.tech/api/v1/persons?name='.$query.'&page=1';
  $result = [];

  if (!mb_strlen($query) >= 3) {
    return $result;
  }

  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');

  curl_setopt($ch, CURLOPT_URL, $url); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);
  $content = json_decode($data, true);

  if ($content['total']) {
    foreach ($content['items'] as $item => $value) {
      $result[] = array_merge(['_type' => 'staff'], $value);
    }
  }

  $result = array_slice($result, 0, $limit);

  return $result;
}

function WatchFastSearch (string $query, int $limit = 200, string $jwt = '0.0.0', int $staffLimit = 4) {
  $query = urldecode($query);
  $query = rawurlencode($query);
  $url = 'https://kinopoiskapiunofficial.tech/api/v2.1/films/search-by-keyword?keyword='.$query.'&page=1';
  $result = [];

  if (!mb_strlen($query) >= 3) {
    $result['code'] = 404;
    $result['content'] = [];
    $result['total'] = 0;
    return $result;
  }

  // add Metric
  WatchFastSearchAddMetric($query, $jwt);

  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');

  curl_setopt($ch, CURLOPT_URL, $url); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);
  $content = json_decode($data, true);

  $staffList = WatchFastSearchPersonItems($query, $staffLimit);

  if ($content['searchFilmsCountResult'] > 0 || count($staffList)) {
    $count = 0;
    $result['code'] = 200;

    if (count($staffList)) {
      $result['content'] = $staffList;
    }
    foreach ($content['films'] as $item => $value) {
      $count++;
      if ($count > $limit) continue;
      $result['content'][] = [
        '_type' => 'watch',
        'kinopoiskId' => $value['filmId'],
        'nameRu' => $value['nameRu'],
        'type' => $value['type'],
        'year' => $value['year'],
        'posterUrl' => $value['posterUrl'],
        'ratingKinopoisk' => $value['rating']
      ];
    }
    $result['total'] = count($result['content']);
  } else {
    $result['code'] = 404;
    $result['content'] = [];
    $result['total'] = 0;
  }

  return $result;
}

function WatchGetSubscriptions (string $jwt) {
  if (!UserJwtIsValid($jwt)) return ['code' => 404];
  $user = UserJwtDecode($jwt)['data'];

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

  $query_content = "SELECT id, slug, kinopoiskId, nameRu, ratingAgeLimits, ratingKinopoisk, posterUrl, type, year FROM WatchContent WHERE kinopoiskId IN ($kinopoiskIdList) and kinopoiskId != :kinopoiskId ORDER BY FIELD(kinopoiskId, $kinopoiskIdList)";
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

// WatchHistory
function WatchHistoryGetTimeByKpid (int $kinopoiskId, int $uid) {
  $query = "SELECT * FROM WatchHistory WHERE kinopoiskId = :kinopoiskId and uid = :uid ORDER BY time DESC";
  $var = [
    ':kinopoiskId' => $kinopoiskId,
    ':uid' => $uid
  ];
  $result = dbGetOne($query, $var);

  return $result;
}

/**
 * @decsription Если офсет + время меньше текущего - должен добавить в историю запись о просмотре
 * @return Bool - если true - запись добавлена, иначе - false
 */
function WatchHistoryAdd (int $kinopoiskId, int $uid) {
  if (!$kinopoiskId) return false;
  if (!$uid) return false;
  // Время
  $timeOffset = 3600;
  $timeNow = time();

  $historyLast = WatchHistoryGetTimeByKpid($kinopoiskId, $uid);

  // проверка timeOffset
  if (($historyLast['time'] + $timeOffset) >= $timeNow) return false;

  // time offset прошли, добавляем
  $query = "INSERT INTO `WatchHistory` (`id`, `uid`, `kinopoiskId`, `time`) VALUES (NULL, :uid, :kinopoiskId, :time)";
  $var = [
    ':uid' => $uid,
    ':kinopoiskId' => $kinopoiskId,
    ':time' => $timeNow
  ];

  dbAddOne($query, $var);

  return true;
}

function WatchHistoryGet ($jwt) {
  if (!UserJwtIsValid($jwt)) return ['code' => 404];
  $user = UserJwtDecode($jwt)['data'];

  if (!$user['uid']) return ['code' => 404];

  $query_history = "SELECT DISTINCT kinopoiskId, time FROM WatchHistory WHERE uid = :uid ORDER BY time DESC";
  $var_history = [
    ':uid' => $user['uid']
  ];

  $history = dbGetAll($query_history, $var_history);

  if (!count($history) > 0) {
    return [
      'code' => 404,
      'total' => 0,
      'content' => []
    ];
  }

  $kinopoiskIdList = [];
  foreach ($history as $key => $value) {
    $kinopoiskIdList[] = $value['kinopoiskId'];
  }
  $kinopoiskIdList = implode(',', $kinopoiskIdList);

  $query_content = "SELECT id, slug, kinopoiskId, nameRu, ratingAgeLimits, ratingKinopoisk, posterUrl, type, year FROM WatchContent WHERE kinopoiskId IN ($kinopoiskIdList) and kinopoiskId != :kinopoiskId ORDER BY FIELD(kinopoiskId, $kinopoiskIdList)";
  $var_content = [
    ':kinopoiskId' => 0
  ];
  $content = dbGetAll($query_content, $var_content);

  return [
    'code' => 200,
    'total' => count($history),
    'content' => $content
  ];
}

// Trand
function WatchGetTrand ($act = 'ALL') {
  $types = "'FILM','VIDEO','TV_SERIES','MINI_SERIES','TV_SHOW'";

  if ($act === 'FILM') {
    $types = "'FILM'";
  }
  if ($act === 'TV_SERIES') {
    $types = "'TV_SERIES','MINI_SERIES','TV_SHOW'";
  }

  $query = " 
  SELECT wc.id, wc.slug, wc.kinopoiskId, wc.nameRu, wc.ratingAgeLimits, wc.ratingKinopoisk, wc.posterUrl, wc.type, wc.year
  FROM WatchContent wc
  JOIN (
    SELECT wh.kinopoiskId,
      COUNT(DISTINCT wh.uid) as unique_viewers,
      SUM(CASE WHEN wh.time >= UNIX_TIMESTAMP(NOW() - INTERVAL 30 DAY) THEN 1 ELSE 0 END) as recent_views
    FROM WatchHistory wh
    GROUP BY wh.kinopoiskId
  ) rh ON wc.kinopoiskId = rh.kinopoiskId
  WHERE FIND_IN_SET(wc.type, '$types') AND wc.id != :uid
  ORDER BY rh.unique_viewers DESC, rh.recent_views DESC, wc.ratingKinopoisk DESC
  LIMIT 100
  ";

  $payload = [
    ':uid' => 0
  ];

  $content = dbGetAll($query, $payload);

  return [
    'code' => 200,
    'total' => count($content),
    'content' => $content
  ];
}

// Reviews
function WatchReviewsGet ($kinopoiskId) {
  // Reviews
  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, 'https://kinopoiskapiunofficial.tech/api/v1/reviews?filmId='.$kinopoiskId.'&page=1'); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);

  $contentReviews = json_decode($data, true);

  if (!$contentReviews['reviewAllCount'] or $contentReviews['reviewAllCount'] === 0) {
    return [
      'code' => 404,
      'total' => 0,
      'items' => []
    ];
  }

  $result['POSITIVE'] = [];
  $result['NEGATIVE'] = [];
  $result['NEUTRAL'] = [];
  $result['UNKNOWN'] = [];

  foreach ($contentReviews['reviews'] as $item => $value) {
    $obj = [
      'reviewType' => $value['reviewType'],
      'reviewTitle' => $value['reviewTitle'],
      'reviewDescription' => $value['reviewDescription']
    ];

    if ($value['reviewType'] === 'POSITIVE' and count($result['POSITIVE']) < 3) {
      $result['POSITIVE'][] = $obj;
    }

    if ($value['reviewType'] === 'NEGATIVE' and count($result['NEGATIVE']) < 3) {
      $result['NEGATIVE'][] = $obj;
    }

    if ($value['reviewType'] === 'NEUTRAL' and count($result['NEUTRAL']) < 3) {
      $result['NEUTRAL'][] = $obj;
    }

    if ($value['reviewType'] === 'UNKNOWN' and count($result['UNKNOWN']) < 3) {
      $result['UNKNOWN'][] = $obj;
    }
  }

  return [
    'code' => 200,
    'total' => count($result['POSITIVE']) + count($result['NEGATIVE']) + count($result['NEUTRAL']) + count($result['UNKNOWN']),
    'items' => [
      'POSITIVE' => $result['POSITIVE'],
      'NEGATIVE' => $result['NEGATIVE'],
      'NEUTRAL' => $result['NEUTRAL'],
      'UNKNOWN' => $result['UNKNOWN']
    ]
  ];
}

function WatchFactsGet ($kinopoiskId) {
  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, 'https://kinopoiskapiunofficial.tech/api/v2.2/films/'.$kinopoiskId.'/facts'); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);

  $contentFacts = json_decode($data, true);
  if (!$contentFacts['total'] or $contentFacts['total'] === 0) {
    return [
      'code' => 404,
      'total' => 0,
      'content' => []
    ];
  }

  $facts = [];
  foreach ($contentFacts['items'] as $item => $value) {
    if (count($facts) < 10 and $value['type'] === 'FACT') {
      $facts[] = [
        'id' => count($facts),
        'text' => $value['text']
      ];
    }
  }

  return [
    'code' => 200,
    'total' => count($facts),
    'content' => $facts
  ];
}

function WatchSearchByFilters ($country='', $genre='', $order='RATING', $type='', $year='', $page='1') {
  $urlApi = 'https://kinopoiskapiunofficial.tech/api/v2.2/films?page=' . $page;
  if ($country !== '') $urlApi .= '&countries=' . $country;
  if ($genre !== '') $urlApi .= '&genres=' . $genre;
  if ($order !== '') $urlApi .= '&order=' . $order;
  if ($type !== '') $urlApi .= '&type=' . $type;
  if ($year !== '') $urlApi .= '&yearFrom=' . $year;
  if ($year !== '') $urlApi .= '&yearTo=' . $year;

  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, $urlApi); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);

  $contentData = json_decode($data, true);

  if (count($contentData['items']) < 0) return [
    'code' => 404,
    'page' => $page,
    'pages' => 0,
    'items' => []
  ];

  $result= [];
  $count = 0;
  $limit = 100;

  foreach ($contentData['items'] as $item => $value) {
    if ($count > $limit) continue;
    if (!$value['nameRu'] || !$value['posterUrl']) continue;
    $count++;
    $result[] = [
      'id' => strval($value['kinopoiskId']),
      'kinopoiskId' => strval($value['kinopoiskId']),
      'nameRu' => $value['nameRu'],
      'type' => $value['type'],
      'year' => $value['year'],
      'posterUrl' => $value['posterUrl'],
      'ratingKinopoisk' => $value['ratingKinopoisk']
    ];
  }

  return [
    'code' => 200,
    'page' => $page,
    'pages' => $contentData['totalPages'],
    'items' => $result
  ];
}

function WatchGetNameByStaffId ($staff) {
  $urlApi = 'https://kinopoiskapiunofficial.tech/api/v1/staff/' . $staff;

  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, $urlApi); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);

  $contentData = json_decode($data, true);

  if (count($contentData['films']) < 0) return [
    'code' => 404,
    'title' => '',
    'items' => []
  ];

  $list = [];
  $result= [];
  $count = 0;
  $limit = 100;

  foreach ($contentData['films'] as $item => $value) {
    $count++;
    if ($count > $limit) continue;
    if ($list[$value['filmId']]) continue;
    $list[$value['filmId']] = true;
    $result[] = [
      'id' => strval(time()),
      'kinopoiskId' => strval($value['filmId']),
      'nameRu' => $value['nameRu'],
      'type' => $value['type'],
      'year' => $value['year'],
      'posterUrl' => $value['posterUrl'],
      'ratingKinopoisk' => $value['rating']
    ];
  }

  return [
    'code' => 200,
    'title' => $contentData['nameRu'] . ' (' .  $contentData['nameEn'] . ')',
    'items' => $result
  ];
}

function WatchStaffGetByKpid ($kpid) {
  $urlApi = 'https://kinopoiskapiunofficial.tech/api/v1/staff?filmId=' . $kpid;

  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, $urlApi); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);

  $contentData = json_decode($data, true);

  $staff = [];
  if (!count($contentData)) return [
    'code' => 404,
    'staff' => []
  ];

  foreach ($contentData as $item => $value) {
    $staffId = $value['staffId'];
    $nameRu = $value['nameRu'];
    $nameEn = $value['nameEn'];
    $description = $value['description'];
    $posterUrl = $value['posterUrl'];
    $professionText = $value['professionText'];
    $professionKey = $value['professionKey'];

    if ($professionKey === 'UNKNOWN' or !$description or (!$nameRu and !$nameEn)) continue;
    $staff[$professionKey][] = [
      'staffId' => $staffId,
      'nameRu' => $nameRu,
      'nameEn' => $nameEn,
      'description' => $description,
      'posterUrl' => $posterUrl,
      'professionText' => $professionText
    ];
  }

  return [
    'code' => 200,
    'staff' => $staff
  ];
}

function WatchPopularsGet ($page=1) {
  $urlApi = 'https://kinopoiskapiunofficial.tech/api/v2.2/films/top?type=TOP_100_POPULAR_FILMS&page=' . $page;

  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, $urlApi); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);

  $contentData = json_decode($data, true);

  $popular = [];
  if (!$contentData['films']) return [
    'code' => 404,
    'pages' => 0,
    'popular' => []
  ];
  if (!count($contentData['films'])) return [
    'code' => 404,
    'pages' => 0,
    'popular' => []
  ];

  foreach ($contentData['films'] as $item => $value) {
    $popular[] = [
      'id' => strval(time()),
      'kinopoiskId' => strval($value['kinopoiskId']),
      'nameRu' => $value['nameRu'],
      'type' => $value['type'],
      'year' => $value['year'],
      'posterUrl' => $value['posterUrl'],
      'ratingKinopoisk' => $value['ratingKinopoisk']
    ];
  }

  return [
    'code' => 200,
    'page' => $page,
    'pages' => $contentData['totalPages'],
    'popular' => $popular
  ];
}

function WatchGetTrailer ($kpid) {
  $urlApi = 'https://kinopoiskapiunofficial.tech/api/v2.2/films/'.$kpid.'/videos';

  $ch = curl_init();
  $headers = array('accept: application/json', 'x-api-key: eb24ca56-16a8-49ec-91b2-3367940d4c3e');
  curl_setopt($ch, CURLOPT_URL, $urlApi); # URL to post to
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # return into a variable
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); # custom headers, see above
  $data = curl_exec($ch); # run!
  curl_close($ch);

  $contentData = json_decode($data, true);

  return $contentData;
}

function WatchGetTrailerData ($kpid) {
  $data = WatchGetByKpid($kpid);
  $trailers = WatchGetTrailer($kpid);
  if ($trailers['total'] > 0) {
    $data['trailer_src'] = $trailers['items'][0]['url'];

    foreach ($trailers['items'] as $item) {
      if ($item['site'] !== 'YOUTUBE') continue;

      $name = mb_strtolower($item['name']);
      if (str_contains($name, 'рус')) {
        $data['trailer_src'] = $item['url'];
      }
      if (str_contains($name, 'трейлер')) {
        $data['trailer_src'] = $item['url'];
      }
      if (str_contains($name, 'финал')) {
        $data['trailer_src'] = $item['url'];
      }
    }
  } else {
    $data['trailer_src'] = '';
  }
  // $data['trailer_src'] = WatchGetTrailer($kpid);
  return $data;
}

function WatchAdminViewed($jwt) {
  if (!UserJwtIsValid($jwt)) return ['code' => 404];
  $user = UserJwtDecode($jwt)['data'];
  $user_db = UserGetByUid($user['uid']);

  if (!UserCheckPasswordByJwt($jwt)) return ['code' => 404];
  if ($user_db['access'] !== 'author') return ['code' => 404];

  $two_months_ago = new DateTime();
  $two_months_ago->sub(new DateInterval('P2M'));
  $two_months_ago_timestamp = $two_months_ago->getTimestamp();

  $limit = 1500;

  $query = "
  SELECT wc.id, wc.slug, wc.kinopoiskId, wc.nameRu, wc.ratingAgeLimits, wc.ratingKinopoisk, wc.posterUrl, wc.type, wc.year,
         u.uid AS user_uid, u.name AS user_name, u.surname AS user_surname, wh.time AS watch_time
  FROM WatchContent wc
  JOIN WatchHistory wh ON wc.kinopoiskId = wh.kinopoiskId
  JOIN User u ON wh.uid = u.uid
  WHERE wh.time >= :time
  ORDER BY wh.time DESC
  LIMIT $limit;
  ";

  $payload = [
    ':time' => $two_months_ago_timestamp
  ];

  $content = dbGetAll($query, $payload);

  $result = [
    'code' => 200,
    'content' => $content,
    'total' => count($content)
  ];

  return $result;
}
