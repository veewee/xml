<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsl\Configurator;

use XSLTProcessor;

interface Configurator
{
    public function __invoke(XSLTProcessor $processor): XSLTProcessor;
}
