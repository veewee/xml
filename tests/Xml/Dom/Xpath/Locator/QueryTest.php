<?php declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Xpath\Locator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\elements_with_tagname;

final class QueryTest extends TestCase
{
    public function test_it_can_handle_xpath_errors(): void
    {
        $xpath = $this->provideXml()->xpath();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed querying XPath query');
        $this->expectExceptionMessage('[ERROR] : Invalid expression');

        $xpath->query('$p$m``m$^^$^^jibberish');
    }

    
    public function test_it_can_find_xpath_elements(): void
    {
        $xpath = $this->provideXml()->xpath();
        $res = $xpath->query('//item');

        static::assertCount(2, $res);
    }

    
    public function test_it_can_find_xpath_elements_with_node_context(): void
    {
        $doc = $this->provideXml();
        $hello = $doc->locate(elements_with_tagname('hello'))->item(0);

        $xpath = $doc->xpath();
        $res = $xpath->query('./world', $hello);

        static::assertCount(1, $res);
    }

    private function provideXml(): Document
    {
        return Document::fromXmlString(<<<EOXML
            <root>
                <item>Hello</item>
                <item>World</item>
                <hello>
                    <world />
                </hello>
            </root>
        EOXML);
    }
}
