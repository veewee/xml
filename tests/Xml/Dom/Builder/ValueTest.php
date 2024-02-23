<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\value;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class ValueTest extends TestCase
{
    public function test_it_can_build_an_element_with_html_value(): void
    {
        $doc = Document::empty()->toUnsafeDocument();
        $node = element('hello', value('<world>'))($doc);

        static::assertSame('<world>', $node->textContent);
        static::assertSame(xml_string()($node), '<hello>&lt;world&gt;</hello>');
    }
}
