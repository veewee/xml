<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Configurator;

use Webmozart\Assert\Assert;
use XMLReader;
use function Psl\Arr\keys;
use function Psl\Arr\map;
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
 * @param array<int, bool> $options
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
                    'Could not apply property "'.$property.'", Expected one of:'.join(
                        map(
                            keys($allowedOptions),
                            static fn (string $key): string => 'XMLReader::'.$key
                        ),
                        ', '
                    )
                );

                disallow_libxml_false_returns(
                    $reader->setParserProperty($property, $value),
                    'Unable to set the parser property "'.$property.'" to the XML Reader.'
                );
            }

            return $reader;
        }
    )->getResult();
}
