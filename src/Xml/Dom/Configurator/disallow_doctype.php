<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;
use VeeWee\Xml\Exception\DoctypeNotAllowedException;

/**
 * Rejects any document that carries a `<!DOCTYPE ...>` declaration.
 *
 * Documents with a DOCTYPE can be abused for XML eXternal Entity (XXE) attacks,
 * so loading untrusted XML through this configurator guards against that vector.
 *
 * @return Closure(XMLDocument): XMLDocument
 */
function disallow_doctype(): Closure
{
    return static function (XMLDocument $document): XMLDocument {
        if ($document->doctype !== null) {
            throw DoctypeNotAllowedException::create();
        }

        return $document;
    };
}
