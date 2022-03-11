<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Assert;

use DOMNode;
use PHPUnit\Framework\TestCase;
use Psl\Type\Exception\AssertException;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Assert\assert_element;

final class AssertElementTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_elements(?DOMNode $node, bool $expected): void
    {
        if (!$expected) {
            $this->expectException(AssertException::class);
        }

        $actual = assert_element($node);
        static::assertSame($node, $actual);
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
        yield [null, false];
    }
}
