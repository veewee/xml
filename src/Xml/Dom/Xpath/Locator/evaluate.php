<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMNode;
use DOMXPath;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(DOMXPath): mixed
 */
function evaluate(string $query, DOMNode $node = null): callable
{
    return
        /**
         * @return mixed
         */
        static function (DOMXPath $xpath) use ($query, $node) {
            $node = $node ?? $xpath->document->documentElement;

            return disallow_issues(
                /**
                 * @return mixed
                 */
                static fn () => disallow_libxml_false_returns(
                    $xpath->query($query, $node),
                    'Failed evaluating XPath query: '.$query
                ),
            )->getResult();
        };
}
