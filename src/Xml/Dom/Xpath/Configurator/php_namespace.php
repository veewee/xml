<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \Dom\XPath;
use VeeWee\Xml\Xmlns\Xmlns;

/**
 * @return Closure(\Dom\XPath): \Dom\XPath
 */
function php_namespace(): Closure
{
    return static function (\Dom\XPath $xpath): \Dom\XPath {
        namespaces(['php' => Xmlns::phpXpath()->value()])($xpath);

        return $xpath;
    };
}
