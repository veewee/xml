<?php

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath\Configurator;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(
    Configurator\namespaces([
        'soap' => 'http://schemas.xmlsoap.org/wsdl/',
        'xsd' => 'http://www.w3.org/1999/XMLSchema',
    ]),
    Configurator\functions([
        'filter_var'
    ])
);
