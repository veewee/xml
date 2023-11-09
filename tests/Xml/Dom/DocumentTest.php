<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom;

use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Dom\Traverser\Visitor\AbstractVisitor;
use VeeWee\Xml\Dom\Traverser\Visitor\RemoveNamespaces;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Configurator\traverse;
use function VeeWee\Xml\Dom\Configurator\trim_spaces;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Predicate\is_text;

final class DocumentTest extends TestCase
{
    use FillFileTrait;


    public function test_it_can_create_a_document_from_dom(): void
    {
        $document = new DOMDocument();
        $doc = Document::fromUnsafeDocument($document, identity());

        static::assertSame($document, $doc->toUnsafeDocument());
    }


    public function test_it_can_create_an_empty_document(): void
    {
        $document = new DOMDocument();
        $doc = Document::empty();

        static::assertEquals($document, $doc->toUnsafeDocument());
    }


    public function test_it_can_create_a_configured_document(): void
    {
        $document = new DOMDocument();
        $doc = Document::configure(identity());

        static::assertEquals($document, $doc->toUnsafeDocument());
    }


    public function test_it_can_add_various_configurators(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello />',
            trim_spaces(),
            utf8()
        );

        $document = $doc->toUnsafeDocument();
        static::assertFalse($document->preserveWhiteSpace);
        static::assertFalse($document->formatOutput);
        static::assertSame('UTF-8', $document->encoding);
        static::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());
    }


    public function test_it_can_create_a_document_from_xml_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = Document::fromXmlNode(
            $source->documentElement,
            identity()
        );

        static::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());
    }

    /**
     * @see https://github.com/php/php-src/issues/12616
     */
    public function test_it_can_create_a_document_from_xml_node_with_removed_namespaces(): void
    {
        $source = Document::fromXmlString(
            '<hello xmlns="https://hello" />',
            traverse(RemoveNamespaces::all())
        );

        $doc = Document::fromXmlNode(
            $source->map(document_element()),
            identity()
        );

        static::assertXmlStringEqualsXmlString('<hello />', $doc->toXmlString());
    }

    public function test_it_can_create_a_document_from_xml_file(): void
    {
        [$file, $handle] = $this->fillFile($xml = '<hello />');

        $doc = Document::fromXmlFile(
            $file,
            identity()
        );

        static::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());

        fclose($handle);
    }


    public function test_it_can_create_a_document_from_xml_string(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello />',
            identity()
        );

        static::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());
    }


    public function test_it_can_map(): void
    {
        $doc = new DOMDocument();
        $wrapper = Document::fromUnsafeDocument($doc);
        $mapped = $wrapper->map(identity());

        static::assertSame($mapped, $doc);
    }

    public function test_it_can_traverse(): void
    {
        $doc = Document::fromXmlString('<hello>world</hello>');
        $result = $doc->traverse(
            new class() extends AbstractVisitor {
                public function onNodeLeave(DOMNode $node): Action
                {
                    return is_text($node)
                        ? new Action\RemoveNode()
                        : new Action\Noop();
                }
            }
        );

        static::assertXmlStringEqualsXmlString('<hello />', $doc->toXmlString());
        static::assertSame($result, $doc->map(document_element()));
    }

    public function test_it_can_reconfigure_document(): void
    {
        $doc1 = Document::fromXmlString('<hello />');
        $xml1 = $doc1->toXmlString();

        $doc2 = $doc1->reconfigure(identity());
        $xml2 = $doc2->toXmlString();

        static::assertNotSame($doc1, $doc2);
        static::assertSame($doc1->toUnsafeDocument(), $doc2->toUnsafeDocument());
        static::assertXmlStringEqualsXmlString($xml1, $xml2);
    }
}
