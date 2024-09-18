<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Closure;
use Dom\Node;
use Dom\XMLDocument;
use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @return Closure(): XMLDocument
 */
function xml_node_loader(Node $importedNode): Closure
{
    return static fn () => disallow_issues(static function () use ($importedNode): XMLDocument {
        $document = XMLDocument::createEmpty();

        append_external_node($document, $importedNode);

        return $document;
    });
}
