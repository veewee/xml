<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;

interface Configurator
{
    public function __invoke(DOMDocument $document): DOMDocument;
}
