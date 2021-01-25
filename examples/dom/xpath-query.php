<?php

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath\Configurator;
use Psl\Type;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath();
$currentNode = $xpath->querySingle('//products');
$count = $xpath->evaluateNode('count(.//item)', $currentNode, Type\int());

