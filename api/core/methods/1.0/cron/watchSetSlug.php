<?php
require_once(DIR . '/core/helpers/Slug.php');

$limit = $args['limit'];

$watch_query = "SELECT id, kinopoiskId, nameRu, nameEn FROM WatchContent WHERE slug IS NULL and id > :offset LIMIT $limit";
$watch_var = [
  ':offset' => $args['offset']
];

$watch = dbGetAll($watch_query, $watch_var);

function updateSlug(int $id, string $slug) {
  $query_update = "UPDATE `WatchContent` SET slug = :slug WHERE id = :id";
  $var_update = [
    ':id' => $id,
    ':slug' => $slug
  ];

  dbAddOne($query_update, $var_update);
}

foreach ($watch as $item) {
  $nameForSlug = $item['nameRu'] ? $item['nameRu'] : $item['nameEn'];
  if (mb_strlen($nameForSlug)) {
    $prefix = mb_substr($item['kinopoiskId'], 0, 3) . '-';
    $item['slug'] = SlugCreateByText($prefix . $nameForSlug);
    updateSlug($item['id'], $item['slug']);
  }
//  debug($item);
}

$responce = [
  'ok' => $watch,
  'limit' => $args['limit'],
  'offset' => $args['offset']
];
