<?php
function ApiMail(string $to, string $from_user, string $from_email, string $subject = '(No subject)', string $message = '') {
  $from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
  $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

  $headers =  "From: $from_user <$from_email>\r\n".
              "MIME-Version: 1.0" . "\r\n" .
              "Content-type: text/html; charset=UTF-8" . "\r\n";
  return mail($to, $subject, $message, $headers);
}
?>