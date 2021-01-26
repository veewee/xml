<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM;

use DOMDocument;
use function VeeWee\Xml\DOM\documentFromNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\documentFromNode()
 *
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded
 * @uses ::HappyHelpers\callables\after
 * @uses ::VeeWee\Xml\DOM\Configurator\withLoader
 * @uses ::VeeWee\Xml\DOM\document
 * @uses ::VeeWee\Xml\DOM\loader\xmlNodeLoader
 * @uses ::VeeWee\Xml\DOM\manipulator\appendExternalNode
 * @uses ::VeeWee\Xml\DOM\manipulator\importNodeDeeply
 * @uses ::HappyHelpers\xml\detectXmlErrors
 * @uses ::HappyHelpers\xml\useInternalErrors
 */
class documentFromNodeTest extends TestCase
{
    /** @test */
    public function it_can_create_a_document_from_xml_string(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = documentFromNode(
            $source->documentElement,
            fn (DOMDocument $document): DOMDocument => $document
        );

        self::assertInstanceOf(DOMDocument::class, $doc);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }
}
