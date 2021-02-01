<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use XSLTProcessor;

/**
 * @param callable(XSLTProcessor): void $loader
 *
 * @return callable(XSLTProcessor): XSLTProcessor
 */
function loader(callable $loader): callable
{
    return static function (XSLTProcessor $processor) use ($loader): XSLTProcessor {
        $loader($processor);

        return $processor;
    };
}
