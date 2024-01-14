<?php
declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\MatchingNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function Psl\Fun\identity;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;
use function VeeWee\Xml\Reader\Matcher\element_name;

final class MatchingNodeTest extends TestCase
{

    public function test_it_is_a_matching_node(): void
    {
        $match = new MatchingNode(
            $xml = '<hello/>',
            $sequence = new NodeSequence(
                new ElementNode(1, 'hello', 'hello', '', '', [])
            )
        );

        static::assertSame($xml, $match->xml());
        static::assertSame($sequence, $match->nodeSequence());
    }


    public function test_it_can_match(): void
    {
        $match = new MatchingNode(
            '<hello/>',
            new NodeSequence(
                new ElementNode(1, 'hello', 'hello', '', '', [])
            )
        );

        static::assertTrue($match->matches(element_name('hello')));
        static::assertFalse($match->matches(element_name('world')));
    }


    public function test_it_can_transform_into_a_dom_document(): void
    {
        $match = new MatchingNode(
            $xml = '<hello/>',
            new NodeSequence(
                new ElementNode(1, 'hello', 'hello', '', '', [])
            )
        );

        $document = $match->intoDocument(identity());

        static::assertSame($xml, xml_string()($document->map(document_element())));
    }

    public function test_it_can_decode_the_xml(): void
    {
        $match = new MatchingNode(
            $xml = '<hello/>',
            new NodeSequence(
                new ElementNode(1, 'hello', 'hello', '', '', [])
            )
        );

        $decoded = $match->decode(identity());

        static::assertSame(['hello' => ''], $decoded);
    }
}
