<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader\Matcher;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\node_attribute;

final class NodeAttributeTest extends TestCase
{
    public function testIt_returns_true_if_node_attribute_matches(): void
    {
        $matcher = node_attribute('locale', 'nl');
        static::assertTrue($matcher($this->createSequence()));
    }

    
    public function testIt_returns_false_if_node_attribute_does_not_match(): void
    {
        $matcher = node_attribute('locale', 'en');
        static::assertFalse($matcher($this->createSequence()));
    }

    
    public function testIt_returns_false_if_node_attribute_is_not_available(): void
    {
        $matcher = node_attribute('unkown', 'en');
        static::assertFalse($matcher($this->createSequence()));
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
