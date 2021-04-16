<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Node;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Node\ancestors;

final class AncestorsTest extends TestCase
{
    public function test_it_can_detect_ancestors(): void
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <hello>
                <world>
                    <jos />
                    <bos />
                </world>
            </hello>
            EOXML
        );
        $domdoc = $doc->toUnsafeDocument();

        $ancestors = ancestors($domdoc);
        static::assertCount(0, $ancestors);

        $ancestors = ancestors($domdoc->documentElement->firstElementChild->firstElementChild);
        static::assertCount(2, $ancestors);
        static::assertSame('world', $ancestors->item(0)->nodeName);
        static::assertSame('hello', $ancestors->item(1)->nodeName);
    }
}
