<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use Closure;
use \Dom\Node;
use \Dom\NodeList;
use \Dom\XPath;
use InvalidArgumentException;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function Psl\Str\format;
use function VeeWee\Xml\Dom\Assert\assert_dom_node_list;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(\Dom\XPath): \Dom\Node
 */
function query_single(string $query, ?\Dom\Node $node = null): Closure
{
    return
        /**
         * @throws InvalidArgumentException
         * @throws RuntimeException
         */
        static function (\Dom\XPath $xpath) use ($query, $node): \Dom\Node {
            $node = $node ?? $xpath->document->documentElement;
            $list = disallow_issues(
                static fn (): \Dom\NodeList => assert_dom_node_list(
                    disallow_libxml_false_returns(
                        $xpath->query($query, $node),
                        'Failed querying XPath query: '.$query
                    )
                ),
            );

            Assert::count(
                $list,
                1,
                format('Expected to find only one node that matches %s. Got %s', $query, count($list))
            );

            return $list->item(0);
        };
}
