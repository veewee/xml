<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\value;
use function VeeWee\Xml\Dom\Mapper\xml_string;

class ValueTest extends TestCase
{
    /** @test */
    public function it_can_build_an_element_with_html_value(): void
    {
        $doc = new DOMDocument();
        $node = element('hello', value('<world>'))($doc);

        self::assertSame('<world>', $node->nodeValue);
        self::assertSame(xml_string()($node), '<hello>&lt;world&gt;</hello>');
    }
}
