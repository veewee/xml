<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Traverser\Action;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Dom\Traverser\Action\RenameNode;

final class RenameNodeTest extends TestCase
{
    public function test_it_runs_action(): void
    {
        $doc = Document::fromXmlString('<hello><world /></hello>');
        $node = $doc->xpath()->querySingle('//world');

        $action = new RenameNode('jos');

        static::assertInstanceOf(Action::class, $action);
        $action($node);

        static::assertXmlStringEqualsXmlString($doc->toXmlString(), '<hello><jos /></hello>');
    }
}
