<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use Closure;
use DOMNode;
use function Psl\Type\non_empty_string;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Dom\Predicate\is_document;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(DOMNode): non-empty-string
 */
function xml_string(): Closure
{
    return static fn (DOMNode $node): string => disallow_issues(
        static function () use ($node): string {
            $document = detect_document($node);
            $node = is_document($node) ? null : $node;

            return disallow_libxml_false_returns(
                non_empty_string()->assert($document->saveXML($node)),
                'Unable to output XML as string'
            );
        }
    );
}
