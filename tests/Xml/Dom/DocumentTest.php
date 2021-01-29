<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Tests\Helper\FillFileTrait;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Configurator\trim_spaces;
use function VeeWee\Xml\Dom\Configurator\utf8;

class DocumentTest extends TestCase
{
    use FillFileTrait;

    /** @test */
    public function it_can_create_a_document_from_dom(): void
    {
        $document = new DOMDocument();
        $doc = Document::fromUnsafeDocument($document, identity());

        self::assertSame($document, $doc->toUnsafeDocument());
    }

    /** @test */
    public function it_can_create_an_empty_document(): void
    {
        $document = new DOMDocument();
        $doc = Document::empty();

        self::assertEquals($document, $doc->toUnsafeDocument());
    }

    /** @test */
    public function it_can_create_a_configured_document(): void
    {
        $document = new DOMDocument();
        $doc = Document::configure(identity());

        self::assertEquals($document, $doc->toUnsafeDocument());
    }

    /**
     * @test
     */
    public function it_can_add_various_configurators(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello />',
            trim_spaces(),
            utf8()
        );

        $document = $doc->toUnsafeDocument();
        self::assertFalse($document->preserveWhiteSpace);
        self::assertFalse($document->formatOutput);
        self::assertSame('UTF-8', $document->encoding);
        self::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());
    }

    /** @test */
    public function it_can_create_a_document_from_xml_nod(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = Document::fromXmlNode(
            $source->documentElement,
            identity()
        );

        self::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());
    }

    /** @test */
    public function it_can_create_a_document_from_xml_file(): void
    {
        [$file, $handle] = $this->fillFile($xml = '<hello />');

        $doc = Document::fromXmlFile(
            $file,
            identity()
        );

        self::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());

        fclose($handle);
    }

    /** @test */
    public function it_can_create_a_document_from_xml_string(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello />',
            identity()
        );

        self::assertXmlStringEqualsXmlString($xml, $doc->toXmlString());
    }
}
