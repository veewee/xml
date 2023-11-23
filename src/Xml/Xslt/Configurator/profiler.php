<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use Webmozart\Assert\Assert;
use XSLTProcessor;
use function dirname;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @param non-empty-string $profilingFile
 * @return Closure(XSLTProcessor): XSLTProcessor
 */
function profiler(string $profilingFile): Closure
{
    return static fn (XSLTProcessor $processor)
    => disallow_issues(static function () use ($processor, $profilingFile) : XSLTProcessor {
        // ext-xsl doesn't trigger errors if the file does not exist. We'll do it for you!
        Assert::notEmpty($profilingFile);
        $dir = dirname($profilingFile);
        Assert::directory($dir);
        Assert::writable($dir);

        disallow_libxml_false_returns(
            $processor->setProfiling($profilingFile),
            'Could not set a profiling file on the XSLTProcessor.'
        );

        return $processor;
    });
}
