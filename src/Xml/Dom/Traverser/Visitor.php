<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use \Dom\Node;

interface Visitor
{
    public function onNodeEnter(\Dom\Node $node): Action;
    public function onNodeLeave(\Dom\Node $node): Action;
}
