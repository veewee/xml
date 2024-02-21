<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use \DOM\Node;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function get_class;
use function Psl\Dict\map;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 * @param iterable<array-key, \DOM\Node> $sources
 * @return array<array-key, \DOM\Node>
 */
function replace_by_external_nodes(\DOM\Node $target, iterable $sources): array
{
    return disallow_issues(
        /**
         * @return array<array-key, \DOM\Node>
         */
        static function () use ($target, $sources) : array {
            $parentNode = $target->parentNode;
            Assert::notNull($parentNode, 'Could not replace a node without parent node. ('.get_class($target).')');
            $copies = map(
                $sources,
                static fn (\DOM\Node $source): \DOM\Node => import_node_deeply($target, $source)
            );

            foreach ($copies as $copy) {
                $parentNode->insertBefore($copy, $target);
            }

            $parentNode->removeChild($target);

            return $copies;
        }
    );
}
