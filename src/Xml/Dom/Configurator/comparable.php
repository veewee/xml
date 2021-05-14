<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;
use VeeWee\Xml\Dom\Traverser\Visitor\SortAttributes;
use function Psl\Fun\pipe;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function comparable(): callable
{
    return pipe(
        optimize_namespaces(),
        canonicalize(),
        traverse(new SortAttributes()),
    );
}
