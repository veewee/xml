<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\DOM\Xpath;

use function VeeWee\Xml\DOM\xpath\xpath;
use PHPUnit\Framework\TestCase;

/**
 * @covers ::VeeWee\Xml\DOM\xpath\xpath()
 */
class xpathTest extends TestCase
{
    /** @test */
    public function it_can_prepare_xpath(): void
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml = '<hello xmlns="http://namespace"><item /></hello>');

        $xpath = xpath($doc, ['alias' => 'http://namespace']);
        self::assertInstanceOf(\DOMXPath::class, $xpath);

        $aliasedSearch = $xpath->query('alias:item');
        self::assertCount(1, $aliasedSearch);
    }
}
