<?php
echo json_encode(array(
  'statusCode' => $statusCode,
  'error' => $title,
  'description' => str_replace("\n", ' ', $message),
  'errorSource' => 'This is an ingress-level error'
));
?>