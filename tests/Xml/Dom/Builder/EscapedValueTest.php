<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\escaped_value;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class EscapedValueTest extends TestCase
{
    public function test_it_can_build_an_element_with_html_value(): void
    {
        $doc = new DOMDocument();
        $node = element('hello', escaped_value('<wor " ld>'))($doc);

        static::assertSame('<wor " ld>', $node->nodeValue);
        static::assertSame(xml_string()($node), '<hello>&lt;wor " ld&gt;</hello>');
    }
}
