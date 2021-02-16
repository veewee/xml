<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Configurator;

use XMLWriter;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @param callable(XMLWriter): void $opener
 * @return callable(XMLWriter): bool XMLWriter
 */
function open(callable $opener): callable
{
    return static fn (XMLWriter $writer): XMLWriter => disallow_issues(
        static function () use ($writer, $opener): XMLWriter {
            disallow_libxml_false_returns(
                $opener($writer),
                'Could not open the writer stream.'
            );

            return $writer;
        }
    );
}
