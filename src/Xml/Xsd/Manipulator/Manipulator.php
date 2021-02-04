<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsd\Manipulator;

use VeeWee\Xml\Xsd\SchemaCollection;

interface Manipulator
{
    public function __invoke(SchemaCollection $schemaCollection): SchemaCollection;
}
