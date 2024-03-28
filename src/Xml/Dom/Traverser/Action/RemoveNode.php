<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use \DOM\Node;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\remove;

final class RemoveNode implements Action
{
    /**
     * @throws RuntimeException
     */
    public function __invoke(\DOM\Node $currentNode): void
    {
        remove($currentNode);
    }
}
