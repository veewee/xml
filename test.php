<?php

require 'vendor/autoload.php';

$data = \VeeWee\Xml\Encoding\decode(file_get_contents('catalog.xml'));

//var_dump($data);

$xml = \VeeWee\Xml\Encoding\encode($data);

var_dump($xml);