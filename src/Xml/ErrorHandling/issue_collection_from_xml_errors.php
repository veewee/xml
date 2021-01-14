<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling;

use LibXMLError;
use Psl\Arr;
use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;

/**
 * @internal
 *
 * @psalm-param list<LibXMLError> $errors
 */
function issue_collection_from_xml_errors(array $errors): IssueCollection
{
    return new IssueCollection(
        ...Arr\filter_nulls(Arr\map(
            $errors,
            static fn(LibXMLError $error): ?Issue => issue_from_xml_error($error)
        ))
    );
}
