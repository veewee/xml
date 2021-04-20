<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Action;
use function VeeWee\Xml\Dom\Locator\document_element;

final class RemoveNodeTest extends TestCase
{
    public function test_it_runs_action(): void
    {
        $action = new RemoveNode();

        $doc = Document::fromXmlString('<hello who="world"/>');
        $root = $doc->map(document_element());
        $node = $root->attributes->getNamedItem('who');

        static::assertInstanceOf(Action::class, $action);
        $action($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello/>');
    }
}
