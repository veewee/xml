<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use \DOM\XPath;

interface Configurator
{
    public function __invoke(\DOM\XPath $xpath): \DOM\XPath;
}
