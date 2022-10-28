<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Configurator;

use Closure;
use XMLReader;

/*
 * @return \Closure(XMLReader): XMLReader
 */
function substitute_entities(bool $flag = true): Closure
{
    return static fn (XMLReader $reader): XMLReader => parser_options([
        XMLReader::SUBST_ENTITIES => $flag,
    ])($reader);
}
