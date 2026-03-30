<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;
use function VeeWee\Xml\Dom\Manipulator\Document\promote_namespaces as promote_namespaces_manipulator;

/**
 * @return Closure(XMLDocument): XMLDocument
 */
function promote_namespaces(): Closure
{
    return static function (XMLDocument $document): XMLDocument {
        promote_namespaces_manipulator($document);

        return $document;
    };
}
