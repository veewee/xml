<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use DOMXPath;

/**
 * @param non-empty-list<string> $functions
 *
 * @return callable(DOMXPath): DOMXPath
 */
function functions(array $functions): callable
{
    return static function (DOMXPath $xpath) use ($functions) : DOMXPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions($functions);

        return $xpath;
    };
}
