<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use Psl\Result\ResultInterface;
use VeeWee\Xml\Dom\Document;
use XSLTProcessor;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(XSLTProcessor): ResultInterface<bool>
 */
function from_template_document(Document $template): callable
{
    return static fn (XSLTProcessor $processor): ResultInterface => disallow_issues(
        static function () use ($template, $processor): bool {
            disallow_libxml_false_returns(
                $processor->importStyleSheet($template->toUnsafeDocument()),
                'Unable to load XSLT stylesheet document'
            );

            return true;
        }
    );
}
