<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use XSLTProcessor;

/**
 * @param callable(XSLTProcessor): void $loader
 *
 * @return Closure(XSLTProcessor): XSLTProcessor
 */
function loader(callable $loader): Closure
{
    return static function (XSLTProcessor $processor) use ($loader): XSLTProcessor {
        $loader($processor);

        return $processor;
    };
}
