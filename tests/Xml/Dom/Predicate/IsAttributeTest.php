<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Predicate;

use \DOM\Node;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Predicate\is_attribute;

final class IsAttributeTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_attributes(\DOM\Node $node, bool $expected): void
    {
        static::assertSame($expected, is_attribute($node));
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
        yield [$doc->documentElement, false];
        yield [$doc->documentElement->firstElementChild, false];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('attr'), true];
        yield [$doc->documentElement->firstElementChild->firstChild, false];
    }
}
