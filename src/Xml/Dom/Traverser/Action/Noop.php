<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use \DOM\Node;
use VeeWee\Xml\Dom\Traverser\Action;

final class Noop implements Action
{
    public function __invoke(\DOM\Node $currentNode): void
    {
    }
}
