<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Matcher;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\node_attribute;

class NodeAttributeTest extends TestCase
{
    /** @test */
    public function it_returns_true_if_node_attribute_matches(): void
    {
        $matcher = node_attribute('locale', 'nl');
        self::assertTrue($matcher($this->createSequence()));
    }

    /** @test */
    public function it_returns_false_if_node_attribute_does_not_match(): void
    {
        $matcher = node_attribute('locale', 'en');
        self::assertFalse($matcher($this->createSequence()));
    }

    /** @test */
    public function it_returns_false_if_node_attribute_is_not_available(): void
    {
        $matcher = node_attribute('unkown', 'en');
        self::assertFalse($matcher($this->createSequence()));
    }

    private function createSequence(): NodeSequence
    {
        return new NodeSequence(
            new ElementNode(1, 'item', 'item', '', '', [
                new AttributeNode('locale', 'locale', '', '', 'nl')
            ])
        );
    }
}
