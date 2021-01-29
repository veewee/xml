<?php

use VeeWee\Xml\Dom\Document;
use Psl\Type;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath();
$currentNode = $xpath->querySingle('//products');
$count = $xpath->evaluate('count(.//item)', Type\int(), $currentNode);
