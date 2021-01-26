<?php

namespace VeeWee\Xml\Tests\DOM\Xpath\Locator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;

class QueryTest extends TestCase
{
    /** @test */
    public function it_can_handle_xpath_errors(): void
    {
        $xpath = $this->provideXml()->xpath();

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Failed querying XPath query');
        $this->expectErrorMessage('[ERROR] : Invalid expression');

        $xpath->query('$p$m``m$^^$^^jibberish');
    }


    private function provideXml(): Document
    {
        return Document::fromXmlString(<<<EOXML
            <root><item>Hello</item></root>
        EOXML);
    }
}
