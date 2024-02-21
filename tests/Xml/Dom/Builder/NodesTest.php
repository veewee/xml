<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\nodes;

final class NodesTest extends TestCase
{
    public function test_it_can_build_nodes(): void
    {
        $doc = Document::empty()->toUnsafeDocument();
        $nodes = nodes(
            element('hello'),
            element('world'),
            static fn (\DOM\XMLDocument $doc): array => [
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
