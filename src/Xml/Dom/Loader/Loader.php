<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;

interface Loader
{
    public function __invoke(DOMDocument $document): void;
}
