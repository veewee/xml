<?php declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Xpath\Locator;

use DOMElement;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\elements_with_tagname;

final class QuerySingleTest extends TestCase
{
    public function test_it_can_handle_xpath_errors(): void
    {
        $xpath = $this->provideXml()->xpath();

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Failed querying XPath query');
        $this->expectErrorMessage('[ERROR] : Invalid expression');

        $xpath->querySingle('$p$m``m$^^$^^jibberish');
    }

    
    public function test_it_throws_on_multiple_xpath_elements(): void
    {
        $xpath = $this->provideXml()->xpath();

        $this->expectErrorMessage('Expected to find only one node that matches //items. Got 2');
        $xpath->querySingle('//items');
    }

    
    public function test_it_can_find_single_xpath_element(): void
    {
        $xpath = $this->provideXml()->xpath();
        $actual = $xpath->querySingle('//item');

        static::assertInstanceOf(DOMElement::class, $actual);
    }

    
    public function test_it_can_find_single_xpath_element_with_node_context(): void
    {
        $doc = $this->provideXml();
        $hello = $doc->locate(elements_with_tagname('hello'))->item(0);

        $xpath = $doc->xpath();
        $actual = $xpath->querySingle('./world', $hello);

        static::assertInstanceOf(DOMElement::class, $actual);
    }

    private function provideXml(): Document
    {
        return Document::fromXmlString(<<<EOXML
            <root>
                <item>Hello</item>
                <items>Hello</items>
                <items>World</items>
                <hello>
                    <world />
                </hello>
            </root>
        EOXML);
    }
}
