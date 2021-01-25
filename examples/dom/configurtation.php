<?php

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Configurator;
use VeeWee\Xml\Dom\Validator;

$doc = Document::fromXmlFile('data.xml', [
    Configurator\utf8(),
    Configurator\trim_spaces(),
    Configurator\validator(
        Validator\internal_xsd_validator()
    ),
    new MyCustomMergeImportsConfigurator()
]);
