<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Xmlns;

use DOMNameSpaceNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Dom\Document;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Locator\Xmlns\recursive_linked_namespaces;

final class RecursiveLinkedNamespacesTest extends TestCase
{
    public function test_it_can_detect_recursively_linked_namespaces(): void
    {
        $xml = <<<XML
            <root xmlns="http://hello.com">
                <item xmlns:world="http://world.com">1</item>
            </root>
        XML;
        $element = Document::fromXmlString($xml)->locate(document_element());

        $parse = static fn (NodeList $list): array => reduce(
            [...$list],
            static fn (array $map, DOMNameSpaceNode $node) =>  merge($map, [$node->localName => $node->namespaceURI]),
            []
        );

        static::assertSame(
            [
                'xml' => 'http://www.w3.org/XML/1998/namespace',
                'xmlns' => 'http://hello.com',
                'world' => 'http://world.com',
            ],
            $parse(recursive_linked_namespaces($element))
        );
        static::assertSame(
            [
                'xml' => 'http://www.w3.org/XML/1998/namespace',
                'xmlns' => 'http://hello.com',
                'world' => 'http://world.com',
            ],
            $parse(recursive_linked_namespaces($element->firstElementChild))
        );
    }
}
