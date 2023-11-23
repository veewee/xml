<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Transformer;

use Closure;
use VeeWee\Xml\Dom\Document;
use XSLTProcessor;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(XSLTProcessor): string
 */
function document_to_string(Document $document): Closure
{
    return static fn (XSLTProcessor $processor): string => disallow_issues(
        static function () use ($document, $processor): string {
            // Result can also be null ... undocumentedly!
            return (string) disallow_libxml_false_returns(
                $processor->transformToXML($document->toUnsafeDocument()),
                'Unable to apply the XSLT template'
            );
        }
    );
}
