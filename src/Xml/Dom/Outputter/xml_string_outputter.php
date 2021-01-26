<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Outputter;

use DOMDocument;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(DOMDocument): string
 */
function xml_string_outputter(): callable
{
    return static fn (DOMDocument $document): string => disallow_issues(
        static fn (): string => disallow_libxml_false_returns(
            $document->saveXML(),
            'Unable to output XML as string'
        )
    )->getResult();
}
