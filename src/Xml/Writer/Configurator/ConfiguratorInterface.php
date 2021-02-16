<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Configurator;

use XMLWriter;

interface ConfiguratorInterface
{
    public function __invoke(XMLWriter $writer): XMLWriter;
}
