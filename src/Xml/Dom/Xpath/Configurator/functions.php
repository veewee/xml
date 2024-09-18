<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use Dom\XPath;

/**
 * @param non-empty-list<string> $functions
 *
 * @return Closure(XPath): XPath
 */
function functions(array $functions): Closure
{
    return static function (XPath $xpath) use ($functions) : XPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions($functions);

        return $xpath;
    };
}
