<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Xsd;

use \DOM\XMLDocument;
use Psl\Regex\Exception\RuntimeException;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;

/**
 * @throws RuntimeException
 */
function locate_all_xsd_schemas(\DOM\XMLDocument $document): SchemaCollection
{
    return new SchemaCollection(
        ...iterator_to_array(locate_namespaced_xsd_schemas($document)),
        ...iterator_to_array(locate_no_namespaced_xsd_schemas($document))
    );
}
