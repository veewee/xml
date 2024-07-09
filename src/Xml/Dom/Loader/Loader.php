<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use \Dom\XMLDocument;

interface Loader
{
    public function __invoke(): XMLDocument;
}
