<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader\Matcher;

use Generator;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function VeeWee\Xml\Reader\Matcher\document_element;

final class DocumentElementTest extends AbstractMatcherTest
{
    public static function provideRealXmlCases(): Generator
    {
        yield 'document-element' => [
            document_element(),
            $xml = <<<'EOXML'
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>
            </root>
            EOXML,
            [
                $xml,
            ]
        ];
    }

    public static function provideMatcherCases(): Generator
    {
        yield 'it_returns_true_if_its_the_document_element' => [
            document_element(),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', [])
            ),
            true
        ];

        yield 'it_returns_false_if_its_not_the_document_element' => [
            document_element(),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', []),
                new ElementNode(1, 'item', 'item', '', '', []),
            ),
            false
        ];

        yield 'it_solves_code_coverage_issue...?' => [
            static fn (NodeSequence $sequence): bool => document_element()($sequence),
            new NodeSequence(
                new ElementNode(1, 'root', 'root', '', '', [])
            ),
            true
        ];
    }
}
