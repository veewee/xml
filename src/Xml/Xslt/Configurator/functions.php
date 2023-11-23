<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use XSLTProcessor;

/**
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
