<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Dom\XPath;

interface Configurator
{
    public function __invoke(XPath $xpath): XPath;
}
