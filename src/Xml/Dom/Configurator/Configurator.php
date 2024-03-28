<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use \DOM\XMLDocument;

interface Configurator
{
    public function __invoke(\DOM\XMLDocument $document): \DOM\XMLDocument;
}
