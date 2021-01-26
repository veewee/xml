<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\loader;

use function VeeWee\Xml\DOM\loader\xmlStringLoader;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\loader\xmlStringLoader()
 */
class xmlStringLoaderTest extends TestCase
{
    /** @test */
    public function it_can_load_xml_string(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello />';
        $loader = xmlStringLoader($xml);

        self::assertIsCallable($loader);

        $result = $loader($doc);
        self::assertTrue($result);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }

    /** @test */
    public function it_can_not_load_invalid_xml_string(): void
    {
        $doc = new \DOMDocument();
        $xml = '<hello';
        $loader = xmlStringLoader($xml);

        self::assertIsCallable($loader);

        $result = @$loader($doc);
        self::assertFalse($result);
    }
}
