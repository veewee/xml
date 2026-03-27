<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use Closure;
use DOMDocument;
use Dom\XMLDocument;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * Converts a Dom\XMLDocument (PHP 8.4+) into a legacy DOMDocument.
 *
 * This performs an XML round-trip: the new DOM's saveXML() output is loaded
 * into a legacy DOMDocument via loadXML(). The documentURI is preserved.
 *
 * Caveat: line numbers in the resulting DOMDocument may differ from the original
 * because the new DOM's saveXML() can reformat the output (e.g., collapsing
 * multi-line opening tags into single lines).
 *
 * @return Closure(XMLDocument): DOMDocument
 */
function to_unsafe_legacy_document(): Closure
{
    return static fn (XMLDocument $document): DOMDocument => disallow_issues(
        static function () use ($document): DOMDocument {
            $xml = disallow_libxml_false_returns(
                $document->saveXML(),
                'Unable to export XML from Dom\XMLDocument'
            );

            $legacy = new DOMDocument();
            disallow_libxml_false_returns(
                $legacy->loadXML($xml),
                'Unable to load XML into legacy DOMDocument'
            );

            // documentURI must be set AFTER loadXML() because loadXML() resets it.
            $documentUri = $document->documentURI;
            if ($documentUri !== '') {
                $legacy->documentURI = $documentUri;
            }

            return $legacy;
        }
    );
}
