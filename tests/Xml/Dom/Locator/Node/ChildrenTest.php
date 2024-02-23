<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Node;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Node\children;

final class ChildrenTest extends TestCase
{
    public function test_it_can_detect_children(): void
    {
        $doc = Document::fromXmlString('<hello><world /><moon /><!--Comment--></hello>');
        $domdoc = $doc->toUnsafeDocument();

        $children = children($domdoc);
        static::assertCount(1, $children);
        static::assertSame('hello', $children->item(0)->nodeName);

        $children = children($domdoc->documentElement);
        static::assertCount(3, $children);
        static::assertSame('world', $children->item(0)->nodeName);
        static::assertSame('moon', $children->item(1)->nodeName);
        static::assertSame('Comment', $children->item(2)->textContent);
    }
}
