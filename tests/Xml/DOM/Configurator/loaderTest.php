<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Configurator;

use function VeeWee\Xml\DOM\Configurator\withLoader;
use HappyHelpers\xml\Exception\XmlErrorsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\Configurator\withLoader()
 *
 * @uses ::HappyHelpers\xml\detectXmlErrors()
 * @uses ::HappyHelpers\xml\useInternalErrors()
 * @uses ::HappyHelpers\assertions\assertExtensionLoaded()
 * @uses ::HappyHelpers\iterables\map
 * @uses ::HappyHelpers\iterables\toList
 * @uses ::HappyHelpers\strings\stringFromIterable
 * @uses ::HappyHelpers\xml\formatError
 * @uses ::HappyHelpers\xml\formatLevel
 * @uses \HappyHelpers\xml\Exception\XmlErrorsException
 */
class loaderTest extends TestCase
{
    /** @test */
    public function it_can_load_xml(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello />';
        $loader = function (\DOMDocument $doc) use ($xml): bool {
            $doc->loadXML($xml);

            return true;
        };

        $callable = withLoader($loader);
        self::assertIsCallable($callable);

        $result = $callable($doc);
        self::assertSame($doc, $result);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_can_mark_xml_loading_failed(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello />';
        $loader = function (\DOMDocument $doc) use ($xml): bool {
            $doc->loadXML($xml);

            return false;
        };

        $callable = withLoader($loader);
        self::assertIsCallable($callable);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not load the XML document');
        $callable($doc);
    }

    /** @test */
    public function it_throws_xml_exceptions_on_invalid_content(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello';
        $loader = function (\DOMDocument $doc) use ($xml): bool {
            return $doc->loadXML($xml);
        };

        $callable = withLoader($loader);
        self::assertIsCallable($callable);

        $this->expectException(XmlErrorsException::class);
        $callable($doc);
    }
}
