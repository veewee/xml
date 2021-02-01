<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Xpath\Configurator;

use DOMXPath;

interface Configurator
{
    public function __invoke(DOMXPath $xpath): DOMXPath;
}
