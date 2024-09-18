<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use Dom\XPath;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @return Closure(XPath): XPath
 */
function php_namespace(): Closure
{
    return static function (XPath $xpath): XPath {
        namespaces(['php' => Xmlns::phpXpath()->value()])($xpath);

        return $xpath;
    };
}
