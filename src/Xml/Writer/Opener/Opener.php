<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Opener;

use XMLWriter;

interface Opener
{
    public function __invoke(XMLWriter $writer): bool;
}
