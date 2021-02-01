<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Loader;

use XSLTProcessor;

interface Loader
{
    public function __invoke(XSLTProcessor $processor): void;
}
