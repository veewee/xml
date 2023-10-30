<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\namespaced_attribute;

final class NamespacedAttributeTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'namespaced' => [
            namespaced_attribute('https://users', 'country'),
            <<<'EOXML'
            <root xmlns:u="https://users" xmlns:invalid="https://invalid">
                <user u:country="BE">Jos</user>
                <user invalid:country="BE">Bos</user>
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
                new AttributeNode('locale', 'locale', 'https://x', 'x', 'nl')
            ])
        );

        yield 'it_returns_true_if_attribute_name_matches' => [
            namespaced_attribute('https://x', 'locale'),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_attribute_name_is_not_available' => [
            namespaced_attribute('https://x', 'unknown'),
            $sequence,
            false
        ];

        yield 'it_returns_false_if_namespace_does_not_match' => [
            namespaced_attribute('https://invalid', 'locale'),
            $sequence,
            false
        ];
    }
}
