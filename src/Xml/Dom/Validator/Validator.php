<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Validator;

use \DOM\XMLDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;

interface Validator
{
    public function __invoke(\DOM\XMLDocument $document): IssueCollection;
}
