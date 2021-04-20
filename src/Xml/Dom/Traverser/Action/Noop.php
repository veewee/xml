<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;

final class Noop implements Action
{
    public function __invoke(DOMNode $currentNode): void
    {
    }
}
