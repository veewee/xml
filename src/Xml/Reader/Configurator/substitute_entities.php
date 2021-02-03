<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Configurator;

use XMLReader;

/*
 * @return callable(XMLReader): XMLReader
 */
function substitute_entities(bool $flag = true): callable
{
    return static fn (XMLReader $reader): XMLReader => parser_options([
        XMLReader::SUBST_ENTITIES => $flag,
    ])($reader);
}
