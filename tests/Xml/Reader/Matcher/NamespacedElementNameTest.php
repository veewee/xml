<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\namespaced_element;

final class NamespacedElementNameTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'namespaced' => [
            namespaced_element('https://users', 'user'),
            <<<'EOXML'
            <root xmlns:u="https://users" xmlns:invalid="https://invalid">
                <u:user>Jos</u:user>
                <invalid:user>Bos</invalid:user>
                <u:user>Mos</u:user>
            </root>
            EOXML,
            [
                '<u:user xmlns:u="https://users">Jos</u:user>',
                '<u:user xmlns:u="https://users">Mos</u:user>'
            ]
        ];
    }

    public static function provideMatcherCases(): Generator
    {
        $sequence = new NodeSequence(
            new ElementNode(1, 'x:item', 'item', 'https://x', 'x', [])
        );

        yield 'it_returns_true_if_element_name_matches' => [
            namespaced_element('https://x', 'item'),
            $sequence,
            true
        ];

        yield 'it_returns_false_if_element_name_does_not_match' => [
            namespaced_element('https://x', 'other'),
            $sequence,
            false
        ];

        yield 'it_returns_false_if_element_namespace_does_not_match' => [
            namespaced_element('https://invalid', 'item'),
            $sequence,
            false
        ];
    }
}
