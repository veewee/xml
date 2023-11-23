<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use DOMXPath;

/**
 * @param non-empty-list<string> $functions
 *
 * @return Closure(DOMXPath): DOMXPath
 */
function functions(array $functions): Closure
{
    return static function (DOMXPath $xpath) use ($functions) : DOMXPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions($functions);

        return $xpath;
    };
}
