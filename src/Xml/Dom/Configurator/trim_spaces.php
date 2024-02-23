<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function trim_spaces(): Closure
{
    return static function (\DOM\XMLDocument $document): \DOM\XMLDocument {
        // TODO :  not fully implemented yet in the new API
        //$document->preserveWhiteSpace = false;
        $document->formatOutput = false;

        return $document;
    };
}
