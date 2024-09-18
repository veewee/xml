<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use Dom\Node;

interface Action
{
    public function __invoke(Node $currentNode): void;
}
