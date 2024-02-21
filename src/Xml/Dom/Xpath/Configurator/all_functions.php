<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \DOM\XPath;

/**
 * @return Closure(\DOM\XPath): \DOM\XPath
 */
function all_functions(): Closure
{
    return static function (\DOM\XPath $xpath): \DOM\XPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions();

        return $xpath;
    };
}
