<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\node_attribute;

/**
 * @deprecated Use attribute_value instead! This will be removed in next major version
 */
final class NodeAttributeTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'users' => [
            node_attribute('country', 'BE'),
            <<<'EOXML'
            <root>
                <user country="BE">Jos</user>
                <user country="FR">Bos</user>
                <user country="BE">Mos</user>
            </root>
            EOXML,
            [
                '<user country="BE">Jos</user>',
                '<user country="BE">Mos</user>',
            ]
        ];
        yield 'namespaced' => [
            node_attribute('u:country', 'BE'),
            <<<'EOXML'
            <root xmlns:u="https://users">
                <user u:country="BE">Jos</user>
                <user u:country="FR">Bos</user>
                <user u:country="BE">Mos</user>
            </root>
            EOXML,
            [
                '<user xmlns:u="https://users" u:country="BE">Jos</user>',
                '<user xmlns:u="https://users" u:country="BE">Mos</user>'
            ]
        ];
    }

    public static function provideMatcherCases(): Generator
    {
        $sequence = new NodeSequence(
            new ElementNode(1, 'item', 'item', '', '', [
                new AttributeNode('locale', 'locale', '', '', 'nl')
            ])
        );

        yield 'it_returns_true_if_node_attribute_matches' => [
            node_attribute('locale', 'nl'),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_node_attribute_does_not_match' => [
            node_attribute('locale', 'en'),
            $sequence,
            false
        ];

        yield 'it_returns_false_if_node_attribute_is_not_available' => [
            node_attribute('unkown', 'en'),
            $sequence,
            false
        ];
    }
}
