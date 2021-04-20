<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use DOMNode;

interface Action
{
    public function __invoke(DOMNode $currentNode): void;
}
