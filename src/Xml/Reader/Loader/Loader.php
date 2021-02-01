<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Loader;

use XMLReader;

interface Loader
{
    public function __invoke(): XMLReader;
}
