<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Dom\XMLDocument;

interface Configurator
{
    public function __invoke(XMLDocument $document): XMLDocument;
}
