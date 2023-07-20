<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\nodes;

final class NodesTest extends TestCase
{
    public function test_it_can_build_nodes(): void
    {
        $doc = new DOMDocument();
        $nodes = nodes(
            element('hello'),
            element('world'),
            static fn (DOMDocument $doc): array => [
                element('many1')($doc),
                element('many2')($doc),
            ],
        )($doc);

        static::assertCount(4, $nodes);
        static::assertSame('hello', $nodes[0]->nodeName);
        static::assertSame('world', $nodes[1]->nodeName);
        static::assertSame('many1', $nodes[2]->nodeName);
        static::assertSame('many2', $nodes[3]->nodeName);
    }
}
