<?php

function getByUser(string $jwt) {
  $uid = 1;

  $user = UserJwtDecode($jwt);
  if ($user['data']['uid']) {
    $uid = $user['data']['uid'];
  }

  $query = "
    WITH UserWatchHistory AS (
      SELECT wc.kinopoiskId, COUNT(*) as watch_count
      FROM WatchContent wc
      JOIN WatchHistory wh ON wc.kinopoiskId = wh.kinopoiskId
      WHERE wh.uid = :uid
      GROUP BY wc.kinopoiskId
    ),
    AllUsersWatchHistory AS (
      SELECT wc.kinopoiskId, COUNT(DISTINCT wh.uid) as total_users_watched
      FROM WatchContent wc
      JOIN WatchHistory wh ON wc.kinopoiskId = wh.kinopoiskId
      GROUP BY wc.kinopoiskId
    ),
    UserSimilarity AS (
      SELECT uw.kinopoiskId, SUM(uw.watch_count * auw.total_users_watched) as similarity_score
      FROM UserWatchHistory uw
      JOIN AllUsersWatchHistory auw ON uw.kinopoiskId = auw.kinopoiskId
      GROUP BY uw.kinopoiskId
    ),
    RecommendedContent AS (
      SELECT ws.kinopoiskIdList, us.similarity_score,
             ROW_NUMBER() OVER (ORDER BY us.similarity_score DESC) as rn
      FROM WatchSimilars ws
      JOIN UserSimilarity us ON ws.kinopoiskId = us.kinopoiskId
    )
    SELECT DISTINCT wc.kinopoiskId, wc.nameRu, wc.type, wc.ratingKinopoisk, wc.genres, wc.year
    FROM WatchContent wc
    JOIN RecommendedContent rc ON FIND_IN_SET(wc.kinopoiskId, rc.kinopoiskIdList) > 0
    WHERE rc.rn <= 20
    ORDER BY wc.ratingKinopoisk DESC, wc.year DESC;
  ";

  $payload = [
    ':uid' => $uid,
  ];

  $content = dbGetAll($query, $payload);

  $result = [
    'content' => $content,
    'total' => count($content)
  ];

  return $result;
}
