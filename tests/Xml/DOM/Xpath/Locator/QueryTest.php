<?php

namespace VeeWee\Xml\Tests\DOM\Xpath\Locator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath;
use function VeeWee\Xml\Dom\Xpath\Locator\query;

class QueryTest extends TestCase
{

    /** @test */
    public function it_can_locate_xpath(): void
    {

    }

    /** @test */
    public function it_can_handle_xpath_errors(): void
    {
        $doc = Document::fromXmlString(<<<EOXML
<root><item>Hello</item></root>
EOXML
);
        $xpath = $doc->xpath();


        $result = $xpath->query('$p$m``m$^^$^^jibberish');

        self::assertSame(true, false);

    }

}
