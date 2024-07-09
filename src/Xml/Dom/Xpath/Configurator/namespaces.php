<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Closure;
use \Dom\XPath;

/**
 * @param array<string, string> $namespaces
 *
 * @return Closure(\Dom\XPath): \Dom\XPath
 */
function namespaces(array $namespaces): Closure
{
    return static function (\Dom\XPath $xpath) use ($namespaces) : \Dom\XPath {
        foreach ($namespaces as $prefix => $namespaceURI) {
            $xpath->registerNamespace($prefix, $namespaceURI);
        }

        return $xpath;
    };
}
