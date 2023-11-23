<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use Closure;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Assert\assert_dom_node_list;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(DOMXPath): NodeList<DOMNode>
 */
function query(string $query, DOMNode $node = null): Closure
{
    return static function (DOMXPath $xpath) use ($query, $node): NodeList {
        $node = $node ?? $xpath->document->documentElement;

        $list = disallow_issues(
            static fn (): DOMNodeList => assert_dom_node_list(
                disallow_libxml_false_returns(
                    $xpath->query($query, $node),
                    'Failed querying XPath query: '.$query
                )
            ),
        );

        return NodeList::fromDOMNodeList($list);
    };
}
