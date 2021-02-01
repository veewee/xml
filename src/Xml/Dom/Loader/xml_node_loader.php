<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use DOMNode;

use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

/**
 * @return callable(DOMDocument): void
 */
function xml_node_loader(DOMNode $importedNode): callable
{
    return static function (DOMDocument $document) use ($importedNode): void {
        load(static fn (): bool => (bool) append_external_node($document, $importedNode));
    };
}
