<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use DOMXPath;

/**
 * @return callable(DOMXPath): DOMXPath
 */
function all_functions(): callable
{
    return static function (DOMXPath $xpath): DOMXPath {
        php_namespace()($xpath);
        $xpath->registerPhpFunctions();

        return $xpath;
    };
}
