<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use Dom\XPath;

/**
 * @param array<string, string> $namespaces
 *
 * @return Closure(XPath): XPath
 */
function namespaces(array $namespaces): Closure
{
    return static function (XPath $xpath) use ($namespaces) : XPath {
        foreach ($namespaces as $prefix => $namespaceURI) {
            $xpath->registerNamespace($prefix, $namespaceURI);
        }

        return $xpath;
    };
}
