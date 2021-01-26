<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\manipulator;

use DOMElement;
use function VeeWee\Xml\DOM\manipulator\importNodeDeeply;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\manipulator\importNodeDeeply
 */
class ImportNodeDeeplyTest extends TestCase
{
    /** @test */
    public function it_can_import_a_node(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello />');
        $target = new \DOMDocument();

        $result = importNodeDeeply($source->documentElement, $target);

        self::assertInstanceOf(DOMElement::class, $result);
        self::assertSame('hello', $result->nodeName);
    }

    /** @test */
    public function it_can_import_a_node_into_a_nodes_document(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello />');
        $target = new \DOMDocument();
        $target->loadXML('<hello></hello>');

        $result = importNodeDeeply($source->documentElement, $target->documentElement);

        self::assertInstanceOf(DOMElement::class, $result);
        self::assertSame('hello', $result->nodeName);
    }

    /** @test */
    public function it_can_not_import_an_invalid_node(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello />');
        $target = new \DOMDocument();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');
        importNodeDeeply($source, $target);
    }

    /** @test */
    public function it_can_recursively_import_a_node_based_on_a_target_document_node(): void
    {
        $source = new \DOMDocument();
        $source->loadXML('<hello><world myattrib="myvalue"><name>VeeWee</name></world></hello>');
        $target = new \DOMDocument();

        $result = importNodeDeeply($source->documentElement->firstChild, $target);

        self::assertInstanceOf(DOMElement::class, $result);
        self::assertSame('world', $result->nodeName);
        self::assertSame('myvalue', $result->attributes->getNamedItem('myattrib')->nodeValue);
        self::assertSame('name', $result->firstChild->nodeName);
        self::assertSame('VeeWee', $result->firstChild->nodeValue);
    }
}
