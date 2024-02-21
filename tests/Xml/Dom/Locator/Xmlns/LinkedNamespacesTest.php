<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Xmlns;

use \DOM\NameSpaceNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Dom\Document;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Locator\Xmlns\linked_namespaces;

final class LinkedNamespacesTest extends TestCase
{
    public function test_it_can_detect_linked_namespaces(): void
    {
        $xml = <<<XML
            <root xmlns="http://hello.com" xmlns:world="http://world.com" >
                <item>1</item>        
            </root>
        XML;
        $element = Document::fromXmlString($xml)->locate(document_element());

        $parse = static fn (NodeList $list): array => reduce(
            [...$list],
            static fn (array $map, \DOM\NameSpaceNode $node) =>  merge($map, [$node->localName => $node->namespaceURI]),
            []
        );

        static::assertSame(
            [
                'xml' => 'http://www.w3.org/XML/1998/namespace',
                'world' => 'http://world.com',
                'xmlns' => 'http://hello.com',
            ],
            $parse(linked_namespaces($element))
        );
        static::assertSame([], $parse(linked_namespaces($element->childNodes->item(0))));
    }
}
