<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Action;
use function Psl\Iter\first;
use function VeeWee\Xml\Dom\Builder\element;

final class ReplaceNodeTest extends TestCase
{
    public function test_it_runs_action(): void
    {
        $doc = Document::fromXmlString('<hello><world /></hello>');
        $node = $doc->xpath()->querySingle('//world');
        $new = $doc->build(element('jos'));

        $action = new ReplaceNode(first($new));

        static::assertInstanceOf(Action::class, $action);
        $action($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><jos /></hello>');
    }
}
