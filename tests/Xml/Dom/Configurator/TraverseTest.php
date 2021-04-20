<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use DOMNode;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Dom\Traverser\Visitor\AbstractVisitor;
use function VeeWee\Xml\Dom\Configurator\traverse;
use function VeeWee\Xml\Dom\Predicate\is_text;

final class TraverseTest extends TestCase
{
    public function test_it_can_traverse(): void
    {
        $doc = Document::fromXmlString('<hello>world</hello>', traverse(
            new class() extends AbstractVisitor {
                public function onNodeLeave(DOMNode $node): Action
                {
                    return is_text($node)
                        ? new Action\RemoveNode()
                        : new Action\Noop();
                }
            }
        ));

        static::assertXmlStringEqualsXmlString('<hello />', $doc->toXmlString());
    }
}
