<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use XSLTProcessor;

interface Configurator
{
    public function __invoke(XSLTProcessor $processor): XSLTProcessor;
}
