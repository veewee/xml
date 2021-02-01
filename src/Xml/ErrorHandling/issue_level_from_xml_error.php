<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use LibXMLError;
use VeeWee\Xml\ErrorHandling\Issue\Level;

function issue_level_from_xml_error(LibXMLError $error): ?Level
{
    return match ($error->level) {
        LIBXML_ERR_ERROR => Level::error(),
        LIBXML_ERR_FATAL => Level::fatal(),
        LIBXML_ERR_WARNING => Level::warning(),
        default => null
    };
}
