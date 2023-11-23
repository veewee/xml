<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Configurator;

use Closure;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * \XMLReader::LOADDTD
 * Load DTD but do not validate
 *
 * \XMLReader::DEFAULTATTRS
 * Load DTD and default attributes but do not validate
 *
 * \XMLReader::VALIDATE
 * Load DTD and validate while parsing
 *
 * \XMLReader::SUBST_ENTITIES
 * Substitute entities and expand references
 *
 * @param array<
 *     int-mask<
 *         \XMLReader::LOADDTD,
 *         \XMLReader::VALIDATE,
 *         \XMLReader::DEFAULTATTRS,
 *         \XMLReader::SUBST_ENTITIES
 *     >,
 *     bool> $options
 *
 * @return Closure(XMLReader): XMLReader
 */
function parser_options(array $options): Closure
{
    return static fn (XMLReader $reader): XMLReader => disallow_issues(
        static function () use ($reader, $options): XMLReader {
            foreach ($options as $property => $value) {
                disallow_libxml_false_returns(
                    $reader->setParserProperty($property, $value),
                    'Unable to set the parser property "'.((string) $property).'" to the XML Reader.'
                );
            }

            return $reader;
        }
    );
}
