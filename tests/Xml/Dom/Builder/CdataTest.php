<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use DOMCdataSection;
use DOMDocument;
use PHPUnit\Framework\TestCase;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Builder\cdata;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class CdataTest extends TestCase
{
    public function test_it_can_build_cdata(): void
    {
        $doc = new DOMDocument();
        $node = cdata($data = '<html>hello</html>')($doc);

        static::assertInstanceOf(DOMCdataSection::class, $node);
        static::assertSame($data, $node->textContent);
        static::assertSame(xml_string()($node), '<![CDATA['.$data.']]>');
    }

    public function test_it_can_build_cdata_with_configurators(): void
    {
        $doc = new DOMDocument();
        $node = cdata($data = '<html>hello</html>', identity())($doc);

        static::assertInstanceOf(DOMCdataSection::class, $node);
        static::assertSame($data, $node->textContent);
    }
}
