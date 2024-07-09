<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \Dom\XPath;

/**
 * @return Closure(\Dom\XPath): \Dom\XPath
 */
function all_functions(): Closure
{
    return static function (\Dom\XPath $xpath): \Dom\XPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions();

        return $xpath;
    };
}
