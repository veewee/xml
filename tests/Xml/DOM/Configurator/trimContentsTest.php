<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Configurator;

use function VeeWee\Xml\DOM\Configurator\withTrimmedContents;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\Configurator\trimContents()
 * @covers ::VeeWee\Xml\DOM\Configurator\withTrimmedContents()
 */
class trimContentsTest extends TestCase
{
    /** @test */
    public function it_can_trim_contents(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml = '<hello />');

        $callable = withTrimmedContents();
        self::assertIsCallable($callable);

        $result = $callable($doc);
        self::assertSame($doc, $result);
        self::assertFalse($doc->preserveWhiteSpace);
        self::assertFalse($doc->formatOutput);
        self::assertXmlStringEqualsXmlString($xml, $doc->saveXML());
    }
}
