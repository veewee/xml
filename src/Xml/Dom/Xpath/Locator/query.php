<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMElement;
use DOMXPath;
use DOMNodeList;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(DOMXPath): DOMNodeList<DOMElement>
 */
function query(string $query, \DOMNode $node = null): callable
{
    return static function (DOMXPath $xpath) use ($query, $node): DOMNodeList {
        $node = $node ?? $xpath->document->documentElement;

        return disallow_issues(
            static fn (): DOMNodeList => disallow_libxml_false_returns(
                $xpath->query($query, $node),
                'Failed querying XPath query: '.$query
            ),
        );
    };
}
