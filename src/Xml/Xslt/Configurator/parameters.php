<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use XSLTProcessor;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @param array<string, string> $parameters
 *
 * @return callable(XSLTProcessor): XSLTProcessor
 */
function parameters(string $namespace, array $parameters): callable
{
    return static fn (XSLTProcessor $processor)
        => disallow_issues(static function () use ($processor, $namespace, $parameters) : XSLTProcessor {
        disallow_libxml_false_returns(
            $processor->setParameter($namespace, $parameters),
            'Could not set the provided XSLTProcessor parameters.'
        );

        return $processor;
    })->getResult();
}
