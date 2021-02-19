#!/usr/bin/env php
<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use Psl\Type;
use VeeWee\Xml\Dom\Document;
use function Psl\invariant;

(static function (array $argv) {
    if (!$file = $argv[1] ?? null) {
        throw new InvalidArgumentException('Expected clover.xml as first argument.');
    }

    $xpath = Document::fromXmlFile($file)->xpath();
    $totalElements = $xpath->evaluate('number(/coverage/project/metrics/@elements)', Type\float());
    $checkedElements = $xpath->evaluate('number(/coverage/project/metrics/@coveredelements)', Type\float());

    invariant($totalElements > 0, 'No code metrics could be found!');
    invariant(!is_nan($checkedElements), 'No covered elements could be found!');
    $coverage = round(($checkedElements / $totalElements) * 100, 2);

    if ($coverage !== 100.0) {
        echo('Expected coverage of 100%, only got '.$coverage.'%.'.PHP_EOL);
        exit(1);
    }

    echo 'Got 100% Coverage!'.PHP_EOL;
})($argv);
