<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \Dom\XPath;

/**
 * @param non-empty-list<string> $functions
 *
 * @return Closure(\Dom\XPath): \Dom\XPath
 */
function functions(array $functions): Closure
{
    return static function (\Dom\XPath $xpath) use ($functions) : \Dom\XPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions($functions);

        return $xpath;
    };
}
