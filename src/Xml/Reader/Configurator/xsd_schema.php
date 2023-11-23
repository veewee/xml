<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Configurator;

use Closure;
use Webmozart\Assert\Assert;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(XMLReader): XMLReader
 */
function xsd_schema(string $schemaFile): Closure
{
    return static fn (XMLReader $reader): XMLReader
        => disallow_issues(static function () use ($reader, $schemaFile): XMLReader {
            Assert::fileExists($schemaFile);

            disallow_libxml_false_returns(
                $reader->setSchema($schemaFile),
                'Unable to apply XSD schema to the XML Reader.'
            );

            return $reader;
        });
}
