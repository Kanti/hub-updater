<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

echo dechex(OPENSSL_VERSION_NUMBER) . "\n<br>\n";

$name = "danpros/htmly";
$url = "https://api.github.com/repos/" . $name. "/releases";
$streamContext = stream_context_create(
    array(
        'http' => array(
            'header' => "User-Agent: Awesome-Update-My-Self-" . $name . "\r\nAccept: application/vnd.github.v3+json\r\n",
            'request_fulluri' => true,
        ),
        'ssl' => array(
            'cafile' => dirname(__FILE__) . '/ca_bundle.crt',
            'verify_peer' => true,
        ),
    )
);
echo "\n<br>\n" . (string)strlen(file_get_contents($url, false, $streamContext));
echo "\n<br>\n";
foreach($http_response_header as $line)
{
    echo $line . "\n<br>\n";
}