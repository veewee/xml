<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Closure;
use \DOM\XMLDocument;

use \DOM\Node;
use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

/**
 * @return Closure(\DOM\XMLDocument): void
 */
function xml_node_loader(\DOM\Node $importedNode): Closure
{
    return static function (\DOM\XMLDocument $document) use ($importedNode): void {
        load(static fn (): bool => (bool) append_external_node($document, $importedNode));
    };
}
