<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;
use VeeWee\Xml\Dom\Traverser\Visitor\SortAttributes;
use function VeeWee\Xml\Internal\configure;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function comparable(): Closure
{
    return configure(
        optimize_namespaces(),
        canonicalize(),
        traverse(new SortAttributes()),
    );
}
