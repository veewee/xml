<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Dom\Traverser\Visitor;

abstract class AbstractVisitor implements Visitor
{
    public function onNodeEnter(DOMNode $node): Action
    {
        return new Action\Noop();
    }

    public function onNodeLeave(DOMNode $node): Action
    {
        return new Action\Noop();
    }
}
