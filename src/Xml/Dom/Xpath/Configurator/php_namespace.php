<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use DOMXPath;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @return Closure(DOMXPath): DOMXPath
 */
function php_namespace(): Closure
{
    return static function (DOMXPath $xpath): DOMXPath {
        namespaces(['php' => Xmlns::phpXpath()->value()])($xpath);

        return $xpath;
    };
}
