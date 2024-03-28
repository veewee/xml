<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function get_class;
use function Psl\Dict\map;
use function VeeWee\Xml\Dom\Predicate\is_document_element;
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

            // Documents can only contain one element, so in case of documentElement, we remove it first to avoid errors.
            if (is_document_element($target)) {
                $parentNode->removeChild($target);
                $target = null;
            }

            foreach ($copies as $copy) {
                $parentNode->insertBefore($copy, $target);
            }

            // In case of all other elements : the target only gets removed after replacement.
            if ($target) {
                $parentNode->removeChild($target);
            }

            return $copies;
        }
    );
}
