<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Assert;

use \DOM\Node;
use PHPUnit\Framework\TestCase;
use Psl\Type\Exception\AssertException;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Assert\assert_attribute;

final class AssertAttributeTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_attributes(?\DOM\Node $node, bool $expected): void
    {
        if (!$expected) {
            $this->expectException(AssertException::class);
        }

        $actual = assert_attribute($node);
        static::assertSame($node, $actual);
    }

    public static function provideTestCases()
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <doc>
                <item attr="val" xmlns:foo="http://foo" xmlns="http://x">Hello</item>
            </doc>
            EOXML
        )->toUnsafeDocument();

        yield [$doc, false];
        yield [$doc->documentElement, false];
        yield [$doc->documentElement->firstElementChild, false];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('attr'), true];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('xmlns:foo'), true];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('xmlns'), true];
        yield [$doc->documentElement->firstElementChild->firstChild, false];
        yield [null, false];
    }
}
