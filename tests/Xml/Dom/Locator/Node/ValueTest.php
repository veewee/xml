<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Locator\Node;

use PHPUnit\Framework\TestCase;
use Psl\Type;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\elements_with_tagname;
use function VeeWee\Xml\Dom\Locator\Node\value;

final class ValueTest extends TestCase
{
    public function test_it_can_detect_children(): void
    {
        $doc = Document::fromXmlString(<<<EOXML
            <hello>
                <item>Jos</item>
                <item>1.1</item>
            </hello>
        EOXML);

        $items = $doc->locate(elements_with_tagname('item'));

        static::assertSame('Jos', value($items->item(0), Type\string()));
        static::assertSame('1.1', value($items->item(1), Type\string()));
        static::assertSame(1.1, value($items->item(1), Type\float()));
    }
}
