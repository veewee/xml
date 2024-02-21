<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use \DOM\Node;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function get_class;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @throws RuntimeException
 */
function replace_by_external_node(\DOM\Node $target, \DOM\Node $source): \DOM\Node
{
    return disallow_issues(
        static function () use ($target, $source) : \DOM\Node {
            $parentNode = $target->parentNode;
            Assert::notNull($parentNode, 'Could not replace a node without parent node. ('.get_class($target).')');
            $copy = import_node_deeply($target, $source);

            disallow_libxml_false_returns(
                $parentNode->replaceChild($copy, $target),
                'Could not replace the child node.'
            );

            return $copy;
        }
    );
}
