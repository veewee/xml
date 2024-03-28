<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Assert;

use \DOM\Node;
use PHPUnit\Framework\TestCase;
use Psl\Type\Exception\AssertException;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Assert\assert_document;

final class AssertDocumentTest extends TestCase
{
    /**
     *
     * @dataProvider provideTestCases
     */
    public function test_it_knows_documents(?\DOM\Node $node, bool $expected): void
    {
        if (!$expected) {
            $this->expectException(AssertException::class);
        }

        $actual = assert_document($node);
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

        yield [$doc, true];
        yield [$doc->documentElement, false];
        yield [$doc->documentElement->firstElementChild, false];
        yield [$doc->documentElement->firstElementChild->attributes->getNamedItem('attr'), false];
        yield [$doc->documentElement->firstElementChild->firstChild, false];
        yield [null, false];
    }
}
