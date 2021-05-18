<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Action;

use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Node\rename;

final class RenameNode implements Action
{
    public function __construct(
        private string $newQName
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function __invoke(DOMNode $currentNode): void
    {
        rename($currentNode, $this->newQName);
    }
}
