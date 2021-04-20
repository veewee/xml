<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use DOMNode;

interface Visitor
{
    public function onNodeEnter(DOMNode $node): Action;
    public function onNodeLeave(DOMNode $node): Action;
}
