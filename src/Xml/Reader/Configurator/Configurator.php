<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Configurator;

use XMLReader;

interface Configurator
{
    public function __invoke(XMLReader $reader): XMLReader;
}
