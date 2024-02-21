<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use Closure;
use \DOM\Node;
use \DOM\NodeList as DOMNodeList;
use \DOM\XPath;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Assert\assert_dom_node_list;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(\DOM\XPath): NodeList<\DOM\Node>
 */
function query(string $query, \DOM\Node $node = null): Closure
{
    return static function (\DOM\XPath $xpath) use ($query, $node): NodeList {
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
