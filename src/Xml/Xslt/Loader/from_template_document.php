<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use Closure;
use VeeWee\Xml\Dom\Document;
use XSLTProcessor;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(XSLTProcessor): void
 */
function from_template_document(Document $template): Closure
{
    return static function (XSLTProcessor $processor) use ($template): void {
        disallow_issues(
            static function () use ($template, $processor): void {
                disallow_libxml_false_returns(
                    $processor->importStyleSheet($template->toUnsafeDocument()),
                    'Unable to load XSLT stylesheet document'
                );
            }
        );
    };
}
