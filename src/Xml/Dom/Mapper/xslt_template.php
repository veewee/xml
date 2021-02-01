<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use XSLTProcessor;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(DOMDocument): string
 */
function xslt_template(Document $template): callable
{
    return static fn (DOMDocument $document): string => disallow_issues(
        static function () use ($template, $document): string {
            $proc = new XSLTProcessor();

            disallow_libxml_false_returns(
                $proc->importStyleSheet($template->toUnsafeDocument()),
                'Unable to import XSLT stylesheet'
            );

            // Result can also be null ... undocumentedly!
            return (string) disallow_libxml_false_returns(
                $proc->transformToXML($document),
                'Unable to apply the XSLT template'
            );
        }
    )->getResult();
}
