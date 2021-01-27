<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use DOMDocument;
use DOMNode;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(DOMNode): string
 */
function xml_string(): callable
{
    return static fn (DOMNode $node): string => disallow_issues(
        static function () use ($node): string {
            $document = $node instanceof DOMDocument ? $node : $node->ownerDocument;
            $node = $node instanceof DOMDocument ? null : $node;
            Assert::notNull($document);

            return disallow_libxml_false_returns(
                $document->saveXML($node),
                'Unable to output XML as string'
            );
        }
    )->getResult();
}
