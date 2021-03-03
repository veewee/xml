<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\document_element;

final class DocumentElementTest extends TestCase
{
    public function test_it_can_locate_tags_by_name(): void
    {
        $doc = $this->provideXml();
        $root = $doc->locate(document_element());

        static::assertSame($doc->toUnsafeDocument()->documentElement, $root);
    }

    private function provideXml(): Document
    {
        return Document::fromXmlString(<<<EOXML
            <root>
                <item>Hello</item>
                <hello>
                    <item>World</item>
                </hello>
            </root>
        EOXML);
    }
}
