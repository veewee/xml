<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator;

use PHPUnit\Framework\TestCase;
use Psl\Type;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\elements_with_namespaced_tagname;
use function VeeWee\Xml\Dom\Locator\Node\value;

final class ElementsWithNamespacedTagNameTest extends TestCase
{
    public function test_it_can_locate_tags_by_name(): void
    {
        $doc = $this->provideXml();
        $items = $doc->locate(elements_with_namespaced_tagname('http://hello.world', 'item'));

        static::assertCount(2, $items);
        static::assertSame('Hello', value($items->item(0), Type\string()));
        static::assertSame('World', value($items->item(1), Type\string()));
    }

    public function test_it_returns_nothing_on_invalid_tagname(): void
    {
        $doc = $this->provideXml();
        $items = $doc->locate(elements_with_namespaced_tagname('http://hello.world', ';/?!<thisisnotatagname>'));

        static::assertCount(0, $items);
    }

    public function test_it_returns_nothing_on_invalid_namespace(): void
    {
        $doc = $this->provideXml();
        $items = $doc->locate(elements_with_namespaced_tagname('?!;<notanamespace>', 'item'));

        static::assertCount(0, $items);
    }

    private function provideXml(): Document
    {
        return Document::fromXmlString(<<<EOXML
            <root xmlns:test="http://hello.world">
                <test:item>Hello</test:item>
                <hello>
                    <test:item>World</test:item>
                </hello>
                <item>Regular item</item>
            </root>
        EOXML);
    }
}
