<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Builder;

use \DOM\CdataSection;
use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Builder\cdata;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class CdataTest extends TestCase
{
    public function test_it_can_build_cdata(): void
    {
        $doc = Document::empty()->toUnsafeDocument();
        $node = cdata($data = '<html>hello</html>')($doc);

        static::assertInstanceOf(\DOM\CdataSection::class, $node);
        static::assertSame($data, $node->textContent);
        static::assertSame(xml_string()($node), '<![CDATA['.$data.']]>');
    }

    public function test_it_can_build_cdata_with_configurators(): void
    {
        $doc = Document::empty()->toUnsafeDocument();
        $node = cdata($data = '<html>hello</html>', identity())($doc);

        static::assertInstanceOf(\DOM\CdataSection::class, $node);
        static::assertSame($data, $node->textContent);
    }
}
