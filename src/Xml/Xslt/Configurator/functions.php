<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use XSLTProcessor;

/**
 * TODO : Add support for callables : https://wiki.php.net/rfc/improve_callbacks_dom_and_xsl (either here or through a separate configurator)
 *
 * @param non-empty-list<string> $functions
 *
 * @return Closure(XSLTProcessor): XSLTProcessor
 */
function functions(array $functions): Closure
{
    return static function (XSLTProcessor $processor) use ($functions) : XSLTProcessor {
        $processor->registerPhpFunctions($functions);

        return $processor;
    };
}
