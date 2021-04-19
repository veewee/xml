<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Collection;

use DOMAttr;
use DOMElement;
use DOMNode;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psl\Type;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Dom\Document;
use function Psl\Fun\identity;
use function Psl\Vec\filter;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Locator\elements_with_tagname;
use function VeeWee\Xml\Dom\Predicate\is_element;

final class NodeListTest extends TestCase
{
    private function createXml(): Document
    {
        return Document::fromXmlString(
            <<<EOXML
            <catalog>
                <products>
                    <product id="0">product 0</product>
                    <product id="1">product 1</product>
                    <product id="2">product 2</product>
                    <product id="3">product 3</product>
                </products>
                <prices>
                    <price>0</price>
                    <price>1</price>
                    <price>2</price>
                    <price>3</price>
                </prices>
            </catalog>
            EOXML
        );
    }

    private function root(): NodeList
    {
        return new NodeList($this->createXml()->locate(document_element()));
    }

    private function loadProducts(): NodeList
    {
        return $this->createXml()->locate(elements_with_tagname('product'));
    }


    private function loadPrices(): NodeList
    {
        return $this->createXml()->locate(elements_with_tagname('price'));
    }

    public function test_it_can_be_iterated(): void
    {
        $items = $this->loadProducts();

        static::assertCount(4, $items);
        static::assertIsIterable($items);
        foreach ($items as $item) {
            static::assertInstanceOf(DOMElement::class, $item);
        }
    }

    public function test_it_can_be_created_typed(): void
    {
        $items = NodeList::typed(
            DOMElement::class,
            $items = $this->loadProducts()
        );

        static::assertCount(4, $items);
        foreach ($items as $item) {
            static::assertInstanceOf(DOMElement::class, $item);
        }
    }

    public function test_it_will_trigger_error_when_created_with_invalid_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        NodeList::typed(
            DOMElement::class,
            $this->loadProducts()->eq(0)->first()->attributes
        );
    }

    public function test_it_can_access_items(): void
    {
        $items = $this->loadProducts();

        static::assertSame('0', $items->item(0)->getAttribute('id'));
        static::assertSame('1', $items->item(1)->getAttribute('id'));
        static::assertSame('2', $items->item(2)->getAttribute('id'));
        static::assertSame('3', $items->item(3)->getAttribute('id'));
        static::assertSame(null, $items->item(4));
    }

    
    public function test_it_can_map(): void
    {
        $items = $this->loadProducts()->map(
            static fn (DOMElement $element): string => $element->nodeValue
        );

        static::assertSame(
            [
                'product 0',
                'product 1',
                'product 2',
                'product 3',
            ],
            $items
        );
    }

    
    public function test_it_can_filter(): void
    {
        $all = $this->loadProducts();
        $filtered = $all->filter(
            static fn (DOMElement $element): bool => (bool) ((int)($element->getAttribute('id'))%2)
        );

        static::assertCount(2, $filtered);
        static::assertSame($all->item(1), $filtered->item(0));
        static::assertSame($all->item(3), $filtered->item(1));
    }

    
    public function test_it_can_reduce(): void
    {
        $total = $this->loadPrices()->reduce(
            static fn (int $total, DOMElement $element): int => $total + (int) $element->nodeValue,
            0
        );

        static::assertSame(6, $total);
    }

    public function test_it_can_detect(): void
    {
        $list = $this->root()->detect(
            static fn (DOMElement $element) => filter(
                $element->childNodes,
                static fn (DOMNode $current) => is_element($current)
            )
        );

        static::assertCount(2, $list);
    }

    
    public function test_it_can_query_xpath(): void
    {
        $items = $this->root()->query('./prices/price', identity());
        static::assertCount(4, $items);
        foreach ($items as $item) {
            static::assertSame('price', $item->nodeName);
        }
    }

    
    public function test_it_can_evaluate_xpath(): void
    {
        $evaluated = $this->loadPrices()->evaluate('number(.)', Type\int(), identity());

        static::assertSame([0, 1, 2, 3], $evaluated);
    }

    public function test_it_can_equal(): void
    {
        $prices = $this->loadPrices();

        static::assertSame([$prices->item(0)], [...$prices->eq(0)]);
        static::assertSame([$prices->item(1)], [...$prices->eq(1)]);
        static::assertSame([$prices->item(2)], [...$prices->eq(2)]);
        static::assertSame([$prices->item(3)], [...$prices->eq(3)]);
        static::assertSame([], [...$prices->eq(4)]);
    }
    
    public function test_it_can_get_first(): void
    {
        $prices = $this->loadPrices();

        static::assertSame($prices->item(0), $prices->first());
        static::assertSame(null, NodeList::empty()->first());
    }

    public function test_it_can_get_last(): void
    {
        $prices = $this->loadPrices();

        static::assertSame($prices->item(3), $prices->last());
        static::assertSame(null, NodeList::empty()->last());
    }

    
    public function test_it_can_search_ancestors(): void
    {
        $prices = $this->loadPrices();
        $ancestors = $prices->eq(0)->ancestors();

        static::assertCount(2, $ancestors);
        static::assertSame($prices->item(1)->parentNode, $ancestors->item(0));
        static::assertSame($prices->item(1)->parentNode->parentNode, $ancestors->item(1));
    }

    public function test_it_can_search_children(): void
    {
        $children = $this->root()->children();

        static::assertCount(2, $children);
        static::assertSame('products', $children->item(0)->nodeName);
        static::assertSame('prices', $children->item(1)->nodeName);
    }

    public function test_it_can_search_siblings(): void
    {
        $prices = $this->loadPrices();
        $ancestors = $prices->eq(0)->siblings();

        static::assertCount(3, $ancestors);
        static::assertSame($prices->item(1), $ancestors->item(0));
        static::assertSame($prices->item(2), $ancestors->item(1));
        static::assertSame($prices->item(3), $ancestors->item(2));
    }

    public function test_it_can_validate_types(): void
    {
        $prices = $this->loadPrices();
        $result = $prices->expectAllOfType(DOMElement::class);

        static::assertSame([...$prices], [...$result]);
    }

    public function test_it_can_validate_types_and_fail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $prices = $this->loadPrices();
        $prices->expectAllOfType(DOMAttr::class);
    }
}
