<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Configurator;

use XMLWriter;

interface Configurator
{
    public function __invoke(XMLWriter $writer): XMLWriter;
}
