<?php

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Reader\Reader;
use VeeWee\Xml\Reader\Matcher;

$reader   = Reader::fromXmlFile('large-data.xml');
$provider = $reader->provide(
    Matcher\all(
        Matcher\node_name('item'),
        Matcher\node_attribute('locale', 'nl-BE')
    )
);

foreach ($provider as $nlItem) {
    $dom = Document::fromXmlString($nlItem);
    // Do something with it
}
