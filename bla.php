<?php

require __DIR__ . '/vendor/autoload.php';


$attributeList = [
  'postal_code' => '1024 nz',
  'street'      => 'werengouw',
  'house_number' => '399'
];

$urldata = [
  str_replace(' ', '', $attributeList['postal_code']),
  $attributeList['street'],
  str_replace(' ', '', $attributeList['house_number'])
];


$url = "https://eu1.locationiq.com/v1/search.php?key=b7a32fa378c135&q=" . urlencode(implode(' ', $urldata));

echo $url;


use Curl\Curl;

$curl = new Curl();
//$curl->setOpt(CURLOPT_CUSTOMREQUEST, 'GET');
// $curl->setOpt(CURLOPT_NOBODY, true);
//$curl->setOpt(CURLOPT_HEADER, false);
$curl->get($url, [
  "format" => "json"
]);

if ($curl->error) {
  echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
  echo 'Response:' . "\n";

  if ($curl->response && count($curl->response) > 0) {
    var_dump($curl->response);
  } else {
    echo "nee\n";
    var_dump($curl);
  }
}
