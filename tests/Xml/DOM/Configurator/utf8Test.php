<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Configurator;

use function VeeWee\Xml\DOM\Configurator\withUtf8;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\Configurator\utf8()
 * @covers ::VeeWee\Xml\DOM\Configurator\withUtf8()
 */
class utf8Test extends TestCase
{
    /** @test */
    public function it_can_convert_to_utf8(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml = '<hello />');

        $callable = withUtf8();
        self::assertIsCallable($callable);

        $result = $callable($doc);
        self::assertSame($doc, $result);
        self::assertSame('UTF-8', $doc->encoding);
    }
}
