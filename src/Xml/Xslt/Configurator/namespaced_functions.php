<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use XSLTProcessor;

/**
 * @param array<string, (callable(mixed...): mixed)> $functions
 *
 * @return Closure(XSLTProcessor): XSLTProcessor
 */
function namespaced_functions(string $namespace, array $functions): Closure
{
    return static function (XSLTProcessor $processor) use ($namespace, $functions) : XSLTProcessor {
        foreach ($functions as $functionName => $callback) {
            $processor->registerPhpFunctionNS($namespace, $functionName, $callback);
        }

        return $processor;
    };
}
