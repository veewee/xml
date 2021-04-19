<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMNode;
use DOMNodeList;
use DOMXPath;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @template T of DOMNode
 * @return callable(DOMXPath): NodeList<T>
 */
function query(string $query, DOMNode $node = null): callable
{
    return
        /**
         * @return NodeList<T>
         */
        static function (DOMXPath $xpath) use ($query, $node): NodeList {
            $node = $node ?? $xpath->document->documentElement;

            /** @var DOMNodeList<T> $list */
            $list = disallow_issues(
                static fn (): DOMNodeList => disallow_libxml_false_returns(
                    $xpath->query($query, $node),
                    'Failed querying XPath query: '.$query
                ),
            );

            return NodeList::fromDOMNodeList($list);
        };
}
