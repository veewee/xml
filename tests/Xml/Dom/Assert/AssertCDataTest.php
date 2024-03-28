<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Assert;

use \DOM\Node;
use PHPUnit\Framework\TestCase;
use Psl\Type\Exception\AssertException;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Assert\assert_cdata;

final class AssertCDataTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_cdata(?\DOM\Node $node, bool $expected): void
    {
        if (!$expected) {
            $this->expectException(AssertException::class);
        }

        $actual = assert_cdata($node);
        static::assertSame($node, $actual);
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
        yield [null, false];
    }
}
