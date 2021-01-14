<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use DOMXPath;

/**
 * @return callable(DOMXPath): DOMXPath
 */
function php_namespace(): callable
{
    return static function (DOMXPath $xpath): DOMXPath {
        namespaces(['php' => 'http://php.net/xpath'])($xpath);
    };
}
