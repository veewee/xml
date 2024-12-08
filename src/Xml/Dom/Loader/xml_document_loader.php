<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Closure;
use Dom\XMLDocument;
use function Psl\Type\non_empty_string;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * Loads a copy of current document.
 *
 * @param int $options - bitmask of LIBXML_* constants https://www.php.net/manual/en/libxml.constants.php
 * @return Closure(): XMLDocument
 */
function xml_document_loader(
    XMLDocument $importedDocument,
    int $options = 0,
    ?string $override_encoding = null
): Closure {
    return static fn () => disallow_issues(static function () use ($importedDocument, $options, $override_encoding): XMLDocument {

        if ($importedDocument->documentElement === null) {
            return XMLDocument::createEmpty($importedDocument->xmlVersion, $importedDocument->xmlEncoding);
        }

        return XMLDocument::createFromString(
            non_empty_string()->assert($importedDocument->saveXml()),
            $options,
            $override_encoding
        );
    });
}
