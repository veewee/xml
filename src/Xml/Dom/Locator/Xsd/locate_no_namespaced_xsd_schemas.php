<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use DOMDocument;
use Psl\Regex\Exception\RuntimeException;
use VeeWee\Xml\Xmlns\Xmlns;
use VeeWee\Xml\Xsd\Schema\Schema;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;
use function Psl\Dict\map;
use function Psl\Regex\split;

/**
 * @throws RuntimeException
 */
function locate_no_namespaced_xsd_schemas(DOMDocument $document): SchemaCollection
{
    $schemaNs = Xmlns::xsi()->value();
    $attributes = $document->documentElement->attributes;
    if (!$schemaLocNoNamespace = $attributes->getNamedItemNS($schemaNs, 'noNamespaceSchemaLocation')) {
        return new SchemaCollection();
    }

    /** @psalm-suppress MissingThrowsDocblock - Covered the runtime exception! */
    $parts = split(trim($schemaLocNoNamespace->textContent), '/\s+/');

    return new SchemaCollection(
        ...map(
            $parts,
            static fn (string $location) => Schema::withoutNamespace($location)
        )
    );
}
