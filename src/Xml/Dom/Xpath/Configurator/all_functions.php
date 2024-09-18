<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use Dom\XPath;

/**
 * @return Closure(XPath): XPath
 */
function all_functions(): Closure
{
    return static function (XPath $xpath): XPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions();

        return $xpath;
    };
}
