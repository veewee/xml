<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use XSLTProcessor;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(XSLTProcessor): XSLTProcessor
 */
function profiler(string $profilingFile): callable
{
    return static fn (XSLTProcessor $processor)
    => disallow_issues(static function () use ($processor, $profilingFile) : XSLTProcessor {
        disallow_libxml_false_returns(
            $processor->setProfiling($profilingFile),
            'Could not set a profiling file on the XSLTProcessor.'
        );

        return $processor;
    });
}
