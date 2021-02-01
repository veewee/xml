<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use XSLTProcessor;

/**
 * @param list<string> $functions
 *
 * @return callable(XSLTProcessor): XSLTProcessor
 */
function functions(array $functions): callable
{
    return static function (XSLTProcessor $processor) use ($functions) : XSLTProcessor {
        $processor->registerPhpFunctions($functions);

        return $processor;
    };
}
