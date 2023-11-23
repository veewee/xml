<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use XSLTProcessor;

/**
 * @return Closure(XSLTProcessor): XSLTProcessor
 */
function all_functions(): Closure
{
    return static function (XSLTProcessor $processor): XSLTProcessor {
        $processor->registerPhpFunctions();

        return $processor;
    };
}
