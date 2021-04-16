<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Predicate;

use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Predicate\is_document;

final class IsDocumentTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_documents(DOMNode $node, bool $expected): void
    {
        static::assertSame($expected, is_document($node));
    }

    public function provideTestCases()
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <doc>
                <item attr="val">Hello</item>
            </doc>
            EOXML
        )->toUnsafeDocument();

        yield [$doc, true];
        yield [$doc->documentElement, false];
        yield [$doc->documentElement->firstElementChild, false];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('attr'), false];
        yield [$doc->documentElement->firstElementChild->firstChild, false];
    }
}
