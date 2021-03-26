<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use DOMNode;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function get_class;
use function Psl\Dict\map;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 * @param iterable<array-key, DOMNode> $sources
 * @return array<array-key, DOMNode>
 */
function replace_by_external_nodes(DOMNode $target, iterable $sources): array
{
    return disallow_issues(
        /**
         * @return array<array-key, DOMNode>
         */
        static function () use ($target, $sources) : array {
            $parentNode = $target->parentNode;
            Assert::notNull($parentNode, 'Could not replace a node without parent node. ('.get_class($target).')');
            $copies = map(
                $sources,
                static fn (DOMNode $source): DOMNode => import_node_deeply($target, $source)
            );

            foreach ($copies as $copy) {
                $parentNode->insertBefore($copy, $target);
            }

            $parentNode->removeChild($target);

            return $copies;
        }
    );
}
