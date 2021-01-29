<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMNode;
use DOMXPath;
use Psl\Type\Type;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @template T
 *
 * @param Type<T> $type
 *
 * @return callable(DOMXPath): T
 */
function evaluate(string $query, Type $type, DOMNode $node = null): callable
{
    return
        /**
         * @return T
         */
        static function (DOMXPath $xpath) use ($query, $node, $type) {
            $node = $node ?? $xpath->document->documentElement;

            return disallow_issues(
                /**
                 * @return T
                 */
                static fn () => disallow_libxml_false_returns(
                    $type->coerce($xpath->evaluate($query, $node)),
                    'Failed evaluating XPath query: '.$query
                ),
            )->getResult();
        };
}
