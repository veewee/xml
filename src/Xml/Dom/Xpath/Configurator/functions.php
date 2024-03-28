<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \DOM\XPath;

/**
 * @param non-empty-list<string> $functions
 *
 * @return Closure(\DOM\XPath): \DOM\XPath
 */
function functions(array $functions): Closure
{
    return static function (\DOM\XPath $xpath) use ($functions) : \DOM\XPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions($functions);

        return $xpath;
    };
}
