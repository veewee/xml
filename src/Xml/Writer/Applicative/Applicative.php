<?php declare(strict_types=1);

namespace VeeWee\Xml\Writer\Applicative;

use XMLWriter;

interface Applicative
{
    public function __invoke(XMLWriter $writer): mixed;
}
