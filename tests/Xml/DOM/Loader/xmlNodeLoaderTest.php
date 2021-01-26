<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\loader;

use DOMDocument;
use function VeeWee\Xml\DOM\loader\xmlNodeLoader;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\loader\xmlNodeLoader()
 *
 * @uses ::VeeWee\Xml\DOM\manipulator\appendExternalNode
 * @uses ::VeeWee\Xml\DOM\manipulator\importNodeDeeply
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded
 * @uses ::HappyHelpers\xml\detectXmlErrors
 * @uses ::HappyHelpers\xml\useInternalErrors
 */
class xmlNodeLoaderTest extends TestCase
{
    /** @test */
    public function it_can_load_xml_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = new DOMDocument();
        $loader = xmlNodeLoader($source->documentElement);

        self::assertIsCallable($loader);

        $result = $loader($doc);
        self::assertTrue($result);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_can_not_load_invalid_xml_node(): void
    {
        $source = new DOMDocument();
        $source->loadXML($xml = '<hello />');

        $doc = new DOMDocument();
        $loader = xmlNodeLoader($source);

        self::assertIsCallable($loader);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot import node: Node Type Not Supported');

        $loader($doc);
    }
}
