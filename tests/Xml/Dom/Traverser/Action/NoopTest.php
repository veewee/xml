<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Action;
use function VeeWee\Xml\Dom\Locator\document_element;

final class NoopTest extends TestCase
{
    public function test_it_does_nothing(): void
    {
        $action = new Noop();

        $doc = Document::fromXmlString('<hello/>');
        $node = $doc->map(document_element());

        static::assertInstanceOf(Action::class, $action);
        $action($node);
    }
}
