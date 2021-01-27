<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom;

use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath;
use function VeeWee\Xml\DOM\xpath\xpath;
use PHPUnit\Framework\TestCase;

class XpathTest extends TestCase
{
    /** @test */
    public function it_can_prepare_xpath(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello xmlns="http://namespace"><item /></hello>'
        );
        $xpath = Xpath::fromDocument($doc, Xpath\Configurator\namespaces([
            'alias' => 'http://namespace',
        ]));

        $aliasedSearch = $xpath->query('alias:item');
        self::assertCount(1, $aliasedSearch);
    }
}
