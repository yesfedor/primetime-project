<?php
function errorHandler ($code, $data=[]) {
  global $config;

  switch ($code) {
    default:
      $response_code = 500;
      $responce_title = 'Error on the server';
      $responce_detail = 'Error on the server, please contact the documentation or the administrator';
      $responce_docs_link = '';
      break;
    case 'AppRouterMethod.version_not_found':
      $response_code = 404;
      $responce_title = 'Version not found';
      $responce_detail = 'The specified version was not found, see the documentation';
      $responce_docs_link = '/versions';
      break;
    case 'AppRouterMethod.method_not_found':
      $response_code = 404;
      $responce_title = 'Method not found';
      $responce_detail = 'The specified method was not found, see the documentation';
      $responce_docs_link = '/methods';
      break;
    case 'AppRouterMethod.method_required_args':
      $response_code = 400;
      $responce_title = $data['title'];
      $responce_detail = [
        'error' => 'required argument not specified',
        'description' => $data['detail'],
        'props' => $data['props']
      ];
      $responce_docs_link = '/method' . $data['link'];
      break;
    case 'AppRouterMethod.method_file_unavailable':
      $response_code = 501;
      $responce_title = 'Method is unavailable';
      $responce_detail = 'The method is not available on the server, see the documentation';
      $responce_docs_link = '/method' . $data;
      break;
    case 'AppRouterMethod.before_handler_error':
      $response_code = 400;
      $responce_title = 'Request error';
      $responce_detail = [
        'error' => $data['abortData'],
        'description' => 'An error occurred while processing the data, please refer to the documentation'
      ];
      $responce_docs_link = '/method' . $data['link'];
      break;
    case 'AppRouterMethod.client_id_deprecated':
      $response_code = 419;
      $responce_title = 'client_id deprecated';
      $responce_detail = [
        'error' => 'AppRouterMethod.client_id_deprecated',
        'description' => 'need to get a new one client_id_deprecated'
      ];
      $responce_docs_link = '/errors/client_id_deprecated';
      break;
    case 'AppRouterStatic.method_not_found':
      $response_code = 404;
      $responce_title = 'Method not found';
      $responce_detail = 'The specified method was not found, see the documentation';
      $responce_docs_link = '/static/method';
      break;
  }
  $responce_object = [
    'status' => $response_code,
    'data' => 'Server Api Error',
    'title' => $responce_title,
    'detail' => $responce_detail,
    'link' => $config['docs'] . $responce_docs_link
  ];

  $config['responce']($responce_object, $response_code);
}