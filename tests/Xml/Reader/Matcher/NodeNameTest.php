<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Matcher;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\node_name;

class NodeNameTest extends TestCase
{
    /** @test */
    public function it_returns_true_if_node_name_matches(): void
    {
        $matcher = node_name('item');
        self::assertTrue($matcher($this->createSequence()));
    }

    /** @test */
    public function it_returns_false_if_node_name_does_not_match(): void
    {
        $matcher = node_name('other');
        self::assertFalse($matcher($this->createSequence()));
    }

    private function createSequence(): NodeSequence
    {
        return new NodeSequence(
            new ElementNode(1, 'item', 'item', '', '', [])
        );
    }
}
