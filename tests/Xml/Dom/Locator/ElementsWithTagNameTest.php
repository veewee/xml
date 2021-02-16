<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator;

use PHPUnit\Framework\TestCase;
use Psl\Type;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\elements_with_tagname;
use function VeeWee\Xml\Dom\Locator\Node\value;

final class ElementsWithTagNameTest extends TestCase
{
    public function test_it_can_locate_tags_by_name(): void
    {
        $doc = $this->provideXml();
        $items = $doc->locate(elements_with_tagname('item'));

        static::assertCount(2, $items);
        static::assertSame('Hello', value($items->item(0), Type\string()));
        static::assertSame('World', value($items->item(1), Type\string()));
    }

    public function test_it_returns_nothing_on_invalid_tagname(): void
    {
        $doc = $this->provideXml();
        $items = $doc->locate(elements_with_tagname(';/?!<thisisnotatagname>'));

        static::assertCount(0, $items);
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
