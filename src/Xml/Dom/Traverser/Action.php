<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use \DOM\Node;

interface Action
{
    public function __invoke(\DOM\Node $currentNode): void;
}
