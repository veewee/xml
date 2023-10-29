<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\attribute_local_value;

final class AttributeLocalValueTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'users' => [
            attribute_local_value('country', 'BE'),
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
            attribute_local_value('country', 'BE'),
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
            new ElementNode(1, 'x:item', 'item', 'https://x', 'x', [
                new AttributeNode('x:locale', 'locale', 'x', 'https://x', 'nl')
            ])
        );

        yield 'it_returns_true_if_local_attribute_value_matches' => [
            attribute_local_value('locale', 'nl'),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_local_attribute_value_does_not_match' => [
            attribute_local_value('locale', 'en'),
            $sequence,
            false
        ];

        yield 'it_returns_false_if_local_attribute_value_is_not_available' => [
            attribute_local_value('unkown', 'en'),
            $sequence,
            false
        ];
    }
}
