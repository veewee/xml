<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use DOMNode;
use Psl\Result\ResultInterface;

use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

/**
 * @return callable(DOMDocument): ResultInterface<true>
 */
function xml_node_loader(DOMNode $importedNode): callable
{
    return static function (DOMDocument $document) use ($importedNode): ResultInterface {
        return load(static fn () => (bool) append_external_node($document, $importedNode));
    };
}
