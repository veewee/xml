<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use DOMDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;

interface Validator
{
    public function __invoke(DOMDocument $document): IssueCollection;
}
