<?php

use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\xslt_template;

$doc = Document::fromXmlFile('data.xml');
$xsl = Document::fromXmlFile('xml-to-yaml-converter.xslt');

echo $doc->map(xslt_template($xsl));
