<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use \DOM\XMLDocument;

interface Loader
{
    public function __invoke(\DOM\XMLDocument $document): void;
}
