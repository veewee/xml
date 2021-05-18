<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Node;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Manipulator\Node\remove_namespace;
use function VeeWee\Xml\Dom\Manipulator\Node\rename;
use function VeeWee\Xml\Dom\Xpath\Configurator\namespaces;

final class RenameTest extends TestCase
{
    public function test_it_can_rename_a_simple_element(): void
    {
        $doc = Document::fromXmlString('<hello><item /></hello>');
        $node = $doc->xpath()->querySingle('//item');

        $result = rename($node, 'thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><thing /></hello>');
        static::assertSame($doc->xpath()->querySingle('//thing'), $result);
    }

    public function test_it_can_rename_a_item_in_a_list(): void
    {
        $doc = Document::fromXmlString('<hello><item /><item /><item /></hello>');
        $node = $doc->xpath()->query('//item')->first();

        $result = rename($node, 'thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><thing /><item /><item /></hello>');
        static::assertSame($doc->xpath()->querySingle('//thing'), $result);
    }

    public function test_it_can_rename_an_element_with_children(): void
    {
        $doc = Document::fromXmlString('<hello><item><foo bar="baz" /></item></hello>');
        $node = $doc->xpath()->querySingle('//item');

        $result = rename($node, 'thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><thing><foo bar="baz" /></thing></hello>');
        static::assertSame($doc->xpath()->querySingle('//thing'), $result);
    }

    public function test_it_can_rename_an_element_with_attributes(): void
    {
        $doc = Document::fromXmlString('<hello><item foo="bar"/></hello>');
        $node = $doc->xpath()->querySingle('//item');

        $result = rename($node, 'thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><thing foo="bar" /></hello>');
        static::assertSame($doc->xpath()->querySingle('//thing'), $result);
    }

    public function test_it_can_rename_an_element_with_namespace(): void
    {
        $doc = Document::fromXmlString('<hello><item foo="bar" xmlns="http://ok"/></hello>');
        $node = $doc->xpath(namespaces(['ok' => 'http://ok']))->querySingle('//ok:item');

        $result = rename($node, 'thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><thing foo="bar" xmlns="http://ok" /></hello>');
        static::assertSame($doc->xpath(namespaces(['ok' => 'http://ok']))->querySingle('//ok:thing'), $result);
    }

    public function test_it_can_rename_an_element_with_prefixed_namespace(): void
    {
        $doc = Document::fromXmlString('<hello><a:item a:foo="bar" xmlns:a="http://ok" xmlns:whatever="http://whatever"/></hello>');
        $node = $doc->xpath(namespaces(['a' => 'http://ok']))->querySingle('//a:item');

        $result = rename($node, 'a:thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><a:thing a:foo="bar" xmlns:a="http://ok" xmlns:whatever="http://whatever" /></hello>');
        static::assertSame($doc->xpath(namespaces(['a' => 'http://ok']))->querySingle('//a:thing'), $result);
    }

    public function test_it_can_rename_an_element_and_drop_prefix(): void
    {
        $doc = Document::fromXmlString('<hello><a:item a:foo="bar" xmlns:a="http://ok" xmlns:whatever="http://whatever"/></hello>');
        $node = $doc->xpath(namespaces(['a' => 'http://ok']))->querySingle('//a:item');

        $result = rename($node, 'thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><thing foo="bar" xmlns="http://ok" xmlns:whatever="http://whatever" /></hello>');
        static::assertSame($doc->xpath(namespaces(['a' => 'http://ok']))->querySingle('//a:thing'), $result);
    }

    public function test_it_can_rename_an_element_prefix(): void
    {
        $doc = Document::fromXmlString('<hello><a:item a:foo="bar" xmlns:a="http://ok" xmlns:whatever="http://whatever"/></hello>');
        $node = $doc->xpath(namespaces(['a' => 'http://ok']))->querySingle('//a:item');

        $result = rename($node, 'b:thing');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><b:thing xmlns:b="http://ok" xmlns:whatever="http://whatever" b:foo="bar" /></hello>');
        static::assertSame($doc->xpath(namespaces(['b' => 'http://ok']))->querySingle('//b:thing'), $result);
    }

    public function test_it_can_rename_attributes(): void
    {
        $doc = Document::fromXmlString('<hello who="world"/>');
        $root = $doc->map(document_element());
        $node = $root->attributes->getNamedItem('who');

        $result = rename($node, 'you');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello you="world"/>');
        static::assertSame($root->getAttributeNode('you'), $result);
    }

    public function test_it_can_rename_namespaced_attributes(): void
    {
        $doc = Document::fromXmlString('<hello a:who="world" xmlns:a="http://a"/>');
        $root = $doc->map(document_element());
        $node = $root->getAttributeNode('a:who');

        $result = rename($node, 'a:you');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello a:you="world" xmlns:a="http://a"/>');
        static::assertSame($root->getAttributeNode('a:you'), $result);
    }

    /**
     * https://github.com/php/php-src/blob/1c8bb6d6818c10a691d6836b336bcee003478b44/ext/dom/element.c#L663
     */
    public function test_it_can_not_rename_namespaced_attribute_prefix_when_the_xmlns_is_still_available(): void
    {
        $this->markAsRisky('Broken DOM functionality');
        $doc = Document::fromXmlString('<hello a:who="world" xmlns:a="http://a"/>');
        $root = $doc->map(document_element());
        $node = $root->getAttributeNode('a:who');

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Unable to rename attribute a:who into b:you');

        rename($node, 'b:you');
    }

    public function test_it_can_rename_namespaced_attribute_prefix(): void
    {
        $doc = Document::fromXmlString('<hello a:who="world" xmlns:a="http://a"/>');
        $root = $doc->map(document_element());
        $node = $root->getAttributeNode('a:who');

        // You'll need to manually rename the namespace
        $ns = $root->getAttributeNode('xmlns:a');
        remove_namespace($ns, $root);
        xmlns_attribute('b', $ns->namespaceURI)($root);

        $result = rename($node, 'b:you');

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello b:you="world" xmlns:b="http://a"/>');
        static::assertSame($root->getAttributeNode('b:you'), $result);
    }

    public function test_it_cannot_rename_a_comment(): void
    {
        $doc = Document::fromXmlString('<hello><!-- hello --></hello>');
        $node = $doc->map(
            static fn (DOMDocument $document) => $document->documentElement->firstChild
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Can not rename dom node with type '.$node::class);
        rename($node, 'something');
    }
}
