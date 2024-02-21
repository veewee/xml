<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \DOM\XPath;

/**
 * @param array<string, string> $namespaces
 *
 * @return Closure(\DOM\XPath): \DOM\XPath
 */
function namespaces(array $namespaces): Closure
{
    return static function (\DOM\XPath $xpath) use ($namespaces) : \DOM\XPath {
        foreach ($namespaces as $prefix => $namespaceURI) {
            $xpath->registerNamespace($prefix, $namespaceURI);
        }

        return $xpath;
    };
}
