<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use Dom\Node;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Dom\Traverser\Visitor;

abstract class AbstractVisitor implements Visitor
{
    public function onNodeEnter(Node $node): Action
    {
        return new Action\Noop();
    }

    public function onNodeLeave(Node $node): Action
    {
        return new Action\Noop();
    }
}
