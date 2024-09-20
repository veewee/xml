<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use Dom\XPath;

/**
 * @param array<string, (callable(mixed...): mixed)> $functions
 *
 * @return Closure(XPath): XPath
 */
function namespaced_functions(string $namespace, string $prefix, array $functions): Closure
{
    return static function (XPath $xpath) use ($namespace, $prefix, $functions) : XPath {
        namespaces([$prefix => $namespace])($xpath);
        foreach ($functions as $functionName => $callback) {
            $xpath->registerPhpFunctionNS($namespace, $functionName, $callback);
        }

        return $xpath;
    };
}
