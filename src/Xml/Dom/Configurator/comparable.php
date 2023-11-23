<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use DOMDocument;
use VeeWee\Xml\Dom\Traverser\Visitor\SortAttributes;
use function VeeWee\Xml\Internal\configure;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function comparable(): Closure
{
    return configure(
        optimize_namespaces(),
        canonicalize(),
        traverse(new SortAttributes()),
    );
}
