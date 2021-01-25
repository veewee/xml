<?php

use VeeWee\Xml\Dom\Document;

$reader   = Reader::fromFile('large-data.xml');
$provider = $reader->provide(
    Matcher\all(
        Matcher\currentNodeName('item'),
        Matcher\hasAttribute('locale', 'nl-BE')
    )
);

foreach ($provider as $nlItem) {
    $dom = Document::fromXmlString($nlItem);
    // Do something with it
}
