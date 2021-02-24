<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use DOMXPath;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @return callable(DOMXPath): DOMXPath
 */
function php_namespace(): callable
{
    return static function (DOMXPath $xpath): DOMXPath {
        namespaces(['php' => Xmlns::phpXpath()->value()])($xpath);

        return $xpath;
    };
}
