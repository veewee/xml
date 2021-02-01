<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use XSLTProcessor;

/**
 * @return callable(XSLTProcessor): XSLTProcessor
 */
function all_functions(): callable
{
    return static function (XSLTProcessor $processor): XSLTProcessor {
        $processor->registerPhpFunctions();

        return $processor;
    };
}
