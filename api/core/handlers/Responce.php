<?php
function ResponceDefaultHeaders () {
  header('Access-Control-Allow-Origin: *');
}

function ResponceWithJSON ($response=[], $response_code=200) {
  ResponceDefaultHeaders();

  header("Content-type: application/json; charset=utf-8");
  if ($response_code == 419) $response_code = 200;
  http_response_code($response_code);

  echo json_encode($response, JSON_UNESCAPED_UNICODE + JSON_NUMERIC_CHECK);

  return true;
}

function FinalHandler (int $response_code, array $response=[]) {
  return ['status' => $response_code, 'data' => $response];
}

