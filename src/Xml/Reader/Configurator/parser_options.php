<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Configurator;

use Webmozart\Assert\Assert;
use XMLReader;
use function Psl\Arr\keys;
use function Psl\Arr\map;
use function Psl\Arr\values;
use function Psl\Str\join;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * XMLReader::LOADDTD
 * Load DTD but do not validate
 *
 * XMLReader::DEFAULTATTRS
 * Load DTD and default attributes but do not validate
 *
 * XMLReader::VALIDATE
 * Load DTD and validate while parsing
 *
 * XMLReader::SUBST_ENTITIES
 * Substitute entities and expand references
 *
 * @param array<int-mask<\XMLReader::LOADDTD, \XMLReader::VALIDATE, \XMLReader::DEFAULTATTRS, \XMLReader::SUBST_ENTITIES>, bool> $options
 * @return callable(XMLReader): XMLReader
 */
function parser_options(array $options): callable
{
    $allowedOptions = [
        'LOADDTD' => XMLReader::LOADDTD,
        'VALIDATE' => XMLReader::VALIDATE,
        'DEFAULTATTRS' => XMLReader::DEFAULTATTRS,
        'SUBST_ENTITIES' => XMLReader::SUBST_ENTITIES,
    ];

    return static fn (XMLReader $reader): XMLReader => disallow_issues(
        static function () use ($reader, $options, $allowedOptions): XMLReader {
            foreach ($options as $property => $value) {
                Assert::inArray(
                    $property,
                    $allowedOptions,
                    'Could not apply property "'.((string) $property).'", Expected one of:'.join(
                        values(
                            map(
                                keys($allowedOptions),
                                static fn (string $key): string => 'XMLReader::'.$key
                            )
                        ),
                        ', '
                    )
                );

                disallow_libxml_false_returns(
                    $reader->setParserProperty($property, $value),
                    'Unable to set the parser property "'.((string) $property).'" to the XML Reader.'
                );
            }

            return $reader;
        }
    )->getResult();
}
