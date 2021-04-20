<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_node;

final class ReplaceNode implements Action
{
    public function __construct(
        private DOMNode $newNode
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function __invoke(DOMNode $currentNode): void
    {
        replace_by_external_node($currentNode, $this->newNode);
    }
}
