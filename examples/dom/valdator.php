<?php

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\children;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_element;
use function VeeWee\Xml\Dom\Builder\value;
use function VeeWee\Xml\Dom\Validator\xsd_validator;

$doc = Document::fromXmlFile('data.xml');
$issues = $doc
    ->validate(xsd_validator('schema.xsd'))
    ->filter(static fn (Issue $issue) => $issue->level() > Level::error());

if ($issues->count()) {
    throw new \Exception("OH-OW : We don't understand your XML format: ".$issues->toString());
}
