<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use LibXMLError;
use VeeWee\Xml\ErrorHandling\Issue\Issue;

function issue_from_xml_error(LibXMLError $error): ?Issue
{
    $level = issue_level_from_xml_error($error);
    if ($level === null) {
        return null;
    }

    return new Issue(
        $level,
        $error->code,
        $error->column,
        $error->message,
        $error->file,
        $error->line,
    );
}
