<?php

use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\apply_xslt_template;

$doc = Document::fromXmlFile('data.xml');
$xsl = Document::fromXmlFile('xml-to-yaml-converter.xslt');

echo $doc->map(apply_xslt_template($xsl));
