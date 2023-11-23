<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use XSLTProcessor;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @see https://github.com/php/php-src/blob/286f63809cc9d4fa422799b40d50a63fdaad92e1/ext/xsl/xsltprocessor.c#L665-L719
 * The namespace parameter isn't doing anything at the moment.
 * It doesn't matter what you put into it, it is just not using it.
 * Therefore, it is not available inside this API.
 *
 * @param array<string, string> $parameters
 *
 * @return Closure(XSLTProcessor): XSLTProcessor
 */
function parameters(array $parameters): Closure
{
    return static fn (XSLTProcessor $processor)
        => disallow_issues(static function () use ($processor, $parameters) : XSLTProcessor {
            disallow_libxml_false_returns(
                $processor->setParameter('', $parameters),
                'Could not set the provided XSLTProcessor parameters.'
            );

            return $processor;
        });
}
