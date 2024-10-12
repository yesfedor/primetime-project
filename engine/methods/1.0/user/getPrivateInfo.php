<?php
if ($args['uid'] or $args['domain']) {
  if ($args['uid']) $responce = UserGetPublicByUid($args['uid'], '');
  if ($args['domain']) $responce = UserGetPublicByUid(0, $args['domain']);
}
else $responce = UserHandler(1012);
