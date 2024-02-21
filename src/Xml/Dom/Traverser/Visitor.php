<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use \DOM\Node;

interface Visitor
{
    public function onNodeEnter(\DOM\Node $node): Action;
    public function onNodeLeave(\DOM\Node $node): Action;
}
