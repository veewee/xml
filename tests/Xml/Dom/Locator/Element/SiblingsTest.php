<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Element;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Element\siblings;

final class SiblingsTest extends TestCase
{
    public function test_it_can_detect_siblings(): void
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <hello>
                <item>0</item>
                <item>1</item>
                <item>2</item>
                <item>3</item>
            </hello>
            EOXML
        );
        $domdoc = $doc->toUnsafeDocument();

        $siblings = siblings($domdoc);
        static::assertCount(0, $siblings);

        $siblings = siblings($domdoc->documentElement->firstElementChild);
        static::assertCount(3, $siblings);
        static::assertSame('1', $siblings->item(0)->nodeValue);
        static::assertSame('2', $siblings->item(1)->nodeValue);
        static::assertSame('3', $siblings->item(2)->nodeValue);
    }
}
