<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Predicate;

use \DOM\Node;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Predicate\is_element;

final class IsElementTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_elements(\DOM\Node $node, bool $expected): void
    {
        static::assertSame($expected, is_element($node));
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
        yield [$doc->documentElement->firstElementChild, true];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('attr'), false];
        yield [$doc->documentElement->firstElementChild->firstChild, false];
    }
}
