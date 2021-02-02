<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use InvalidArgumentException;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function Psl\Str\format;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(DOMXPath): DOMElement
 */
function query_single(string $query, DOMNode $node = null): callable
{
    return
        /**
         * @throws InvalidArgumentException
         * @throws RuntimeException
         */
        static function (DOMXPath $xpath) use ($query, $node): DOMElement {
            $node = $node ?? $xpath->document->documentElement;

            /** @var DOMNodeList<DOMElement> $list */
            $list = disallow_issues(
                static fn (): DOMNodeList => disallow_libxml_false_returns(
                    $xpath->query($query, $node),
                    'Failed querying XPath query: '.$query
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
