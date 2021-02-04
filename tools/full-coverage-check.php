#!/usr/bin/env php
<?php

(static function (array $argv) {
    $file = $argv[1] ?? null;
    if (!$file || !file_exists($file)) {
        throw new RuntimeException('Expected clover.xml as first argument. Invalid clover.xml file provided.');
    }

    $xml = simplexml_load_file($file);
    $totalElements = (int) current($xml->xpath('/coverage/project/metrics/@elements'));
    $checkedElements = (int) current($xml->xpath('/coverage/project/metrics/@coveredelements'));
    $coverage = round(($checkedElements / $totalElements) * 100, 2);

    if ($coverage !== 100.0) {
        echo('Expected coverage of 100%, only got '.$coverage.'%.'.PHP_EOL);
        exit(1);
    }

    echo 'Got 100% Coverage!'.PHP_EOL;
})($argv);
