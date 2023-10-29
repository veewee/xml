<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\attribute_name;

final class AttributeNameTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'users' => [
            attribute_name('country'),
            <<<'EOXML'
            <root>
                <user country="BE">Jos</user>
                <user>Bos</user>
                <user country="BE">Mos</user>
            </root>
            EOXML,
            [
                '<user country="BE">Jos</user>',
                '<user country="BE">Mos</user>',
            ]
        ];
        yield 'namespaced' => [
            attribute_name('u:country'),
            <<<'EOXML'
            <root xmlns:u="https://users">
                <user u:country="BE">Jos</user>
                <user>Bos</user>
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

        yield 'it_returns_true_if_attribute_name_matches' => [
            attribute_name('locale'),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_attribute_name_is_not_available' => [
            attribute_name('unkown'),
            $sequence,
            false
        ];
    }
}
