<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Predicate;

use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Predicate\is_document_element;

final class IsDocumentElementTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_document_elements(DOMNode $node, bool $expected): void
    {
        static::assertSame($expected, is_document_element($node));
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

        yield [$doc, false];
        yield [$doc->documentElement, true];
        yield [$doc->documentElement->firstElementChild, false];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('attr'), false];
        yield [$doc->documentElement->firstElementChild->firstChild, false];
    }
}
