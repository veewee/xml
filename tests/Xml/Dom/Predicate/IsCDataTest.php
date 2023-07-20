<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Predicate;

use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Predicate\is_cdata;

final class IsCDataTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_cdata(?DOMNode $node, bool $expected): void
    {
        $actual = is_cdata($node);
        static::assertSame($expected, $actual);
    }

    public function provideTestCases()
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <doc><![CDATA[<html>HELLO</html]]></doc>
            EOXML
        )->toUnsafeDocument();

        yield [$doc, false];
        yield [$doc->documentElement, false];
        yield [$doc->documentElement->firstChild, true];
    }
}
