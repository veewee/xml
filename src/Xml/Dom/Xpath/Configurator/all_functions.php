<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use DOMXPath;

/**
 * @return Closure(DOMXPath): DOMXPath
 */
function all_functions(): Closure
{
    return static function (DOMXPath $xpath): DOMXPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions();

        return $xpath;
    };
}
