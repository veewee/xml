<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use DOMNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @throws RuntimeException
 */
function import_node_deeply(DOMNode $target, DOMNode $source): DOMNode
{
    return disallow_issues(
        static function () use ($target, $source): DOMNode {
            $document = detect_document($target);

            return disallow_libxml_false_returns(
                @$document->importNode($source, true),
                'Cannot import node: Node Type Not Supported'
            );
        }
    );
}
