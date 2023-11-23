<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use Closure;
use DOMNode;
use DOMXPath;
use Psl\Type\TypeInterface;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @template T
 *
 * @param TypeInterface<T> $type
 *
 * @return Closure(DOMXPath): T
 */
function evaluate(string $query, TypeInterface $type, DOMNode $node = null): Closure
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
                static fn () => $type->coerce(
                    disallow_libxml_false_returns(
                        $xpath->evaluate($query, $node),
                        'Failed evaluating XPath query: '.$query
                    )
                ),
            );
        };
}
