<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Xmlns;

use \DOM\NameSpaceNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function Psl\Dict\merge;
use function Psl\Iter\reduce;
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
        $element = Document::fromXmlString($xml)->locateDocumentElement();

        static::assertSame(
            [
                '' => 'http://hello.com',
                'world' => 'http://world.com',
            ],
            $this->parseLinkedNamespaces($element)
        );
        static::assertSame(
            [
                '' => 'http://hello.com',
                'world' => 'http://world.com',
            ],
            $this->parseLinkedNamespaces($element->firstElementChild)
        );
    }

    /**
     * @return array<string, string> - Key : prefix, Value : namespace
     */
    private function parseLinkedNamespaces(\DOM\Element $element): array
    {
        return reduce(
            linked_namespaces($element),
            static fn (array $result, \DOM\NamespaceInfo $info) => merge(
                $result,
                [(string) $info->prefix => $info->namespaceURI]
            ),
            []
        );
    }
}
