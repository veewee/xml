<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use DOMNode;
use Psl\Result\ResultInterface;

use function VeeWee\Xml\Dom\Manipulator\append_external_node;

/**
 * @return callable(DOMDocument): ResultInterface<bool>
 */
function xml_node_loader(DOMNode $importedNode): callable
{
    return static function (DOMDocument $document) use ($importedNode): ResultInterface {
        return load(fn () => (bool) append_external_node($importedNode, $document));
    };
}
