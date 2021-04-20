<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor\AbstractVisitor;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;
use function VeeWee\Xml\Dom\Predicate\is_attribute;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\Dom\Predicate\is_non_empty_text;
use function VeeWee\Xml\Dom\Predicate\is_whitespace;

final class TraverserTest extends TestCase
{
    public function test_it_can_traverse_without_visitors(): void
    {
        $doc = Document::fromXmlString($actual = '<hello/>');
        $doc->traverse();

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), $actual);
    }

    
    public function test_it_can_traverse_single_node(): void
    {
        $doc = Document::fromXmlString($actual = '<hello/>');
        $root = $doc->map(document_element());

        $traverser = new Traverser(
            new class() extends AbstractVisitor {
                public function onNodeLeave(DOMNode $node): Action
                {
                    attribute('who', 'Jos')($node);
                    return new Action\Noop();
                }
            }
        );
        $newRoot = $traverser->traverse($root);

        static::assertXmlStringEqualsXmlString(xml_string()($newRoot), '<hello who="Jos"/>');
    }

    
    public function test_it_can_traverse_attributes(): void
    {
        $doc = Document::fromXmlString($actual = '<hello who="world"/>');
        $doc->traverse(
            new class() extends AbstractVisitor {
                public function onNodeLeave(DOMNode $node): Action
                {
                    if (!is_attribute($node)) {
                        return new Action\Noop();
                    }

                    $node->nodeValue = 'Jos';

                    return new Action\Noop();
                }
            }
        );

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello who="Jos"/>');
    }

    
    public function test_it_can_traverse_child_nodes(): void
    {
        $doc = Document::fromXmlString($actual = '<hello><item>    <![CDATA[Juw]]>    </item></hello>');
        $doc->traverse(
            new class() extends AbstractVisitor {
                public function onNodeLeave(DOMNode $node): Action
                {
                    if (is_non_empty_text($node)) {
                        $node->nodeValue = 'Yo';
                        return new Action\Noop();
                    }
                    if (is_whitespace($node)) {
                        $node->nodeValue = '';
                        return new Action\Noop();
                    }

                    if (is_element($node) && $node->localName === 'item') {
                        $node->setAttribute('type', 'greeting');
                        return new Action\Noop();
                    }

                    return new Action\Noop();
                }
            }
        );

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><item type="greeting">Yo</item></hello>');
    }

    
    public function test_it_can_handle_node_enter_and_leave(): void
    {
        $doc = Document::fromXmlString($actual = '<hello />');
        $doc->traverse(
            new class() extends AbstractVisitor {
                public function onNodeEnter(DOMNode $node): Action
                {
                    if (!is_element($node)) {
                        return new Action\Noop();
                    }

                    $node->setAttribute('enter', 'yes');
                    return new Action\Noop();
                }

                public function onNodeLeave(DOMNode $node): Action
                {
                    if (!is_element($node)) {
                        return new Action\Noop();
                    }

                    $node->setAttribute('leave', 'yes');
                    return new Action\Noop();
                }
            }
        );

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello enter="yes" leave="yes" />');
    }
}
