<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \DOM\XPath;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @return Closure(\DOM\XPath): \DOM\XPath
 */
function php_namespace(): Closure
{
    return static function (\DOM\XPath $xpath): \DOM\XPath {
        namespaces(['php' => Xmlns::phpXpath()->value()])($xpath);

        return $xpath;
    };
}
