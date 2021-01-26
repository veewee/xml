<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\ErrorHandling\Issue;

use VeeWee\Xml\ErrorHandling\Issue\Issue;
use VeeWee\Xml\ErrorHandling\Issue\Level;

trait UseIssueTrait
{
    private function createIssue(Level $level): Issue
    {
        return new Issue(
            $level,
            $code    = 1,
            $column  = 10,
            $message = 'message',
            $file     = 'file.xml',
            $line    = 99
        );
    }
}
