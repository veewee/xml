<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use LibXMLError;
use Psl\Dict;
use Psl\Vec;
use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;

/**
 * @psalm-param list<LibXMLError> $errors
 */
function issue_collection_from_xml_errors(array $errors): IssueCollection
{
    return new IssueCollection(
        ...Vec\filter_nulls(Dict\map(
            $errors,
            static fn (LibXMLError $error): ?Issue => issue_from_xml_error($error)
        ))
    );
}
