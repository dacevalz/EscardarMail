<?php
error_reporting(0);
$host = "172.22.0.3"; // mail container IP within Docker network
$port = 143;
$s = @fsockopen($host, $port, $errno, $errstr, 5);
if ($s) {
    echo "OK";
    fclose($s);
} else {
    echo "FAIL: $errno - $errstr";
}