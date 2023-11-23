<?php declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Xpath\Locator;

use PHPUnit\Framework\TestCase;
use Psl\Type;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\elements_with_tagname;

final class EvaluateTest extends TestCase
{
    public function test_it_can_handle_xpath_errors(): void
    {
        $xpath = $this->provideXml()->xpath();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed querying XPath query');
        $this->expectExceptionMessage('[ERROR] : Invalid expression');

        $xpath->evaluate('$p$m``m$^^$^^jibberish', Type\string());
    }

    
    public function test_it_can_find_xpath_evaluation(): void
    {
        $xpath = $this->provideXml()->xpath();
        $res = $xpath->evaluate('string(//item[1])', Type\string());

        static::assertSame('Hello', $res);
    }

    
    public function test_it_can_find_xpath_elements_with_node_context(): void
    {
        $doc = $this->provideXml();
        $hello = $doc->locate(elements_with_tagname('hello'))->item(0);

        $xpath = $doc->xpath();
        $res = $xpath->evaluate('string(./world)', Type\string(), $hello);

        static::assertSame('Earth', $res);
    }

    private function provideXml(): Document
    {
        return Document::fromXmlString(<<<EOXML
            <root>
                <item>Hello</item>
                <item>World</item>
                <hello>
                    <world>Earth</world>
                </hello>
            </root>
        EOXML);
    }
}
