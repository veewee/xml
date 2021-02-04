<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsd\Schema\Manipulator;

use VeeWee\Xml\Xsd\Schema\SchemaCollection;

interface Manipulator
{
    public function __invoke(SchemaCollection $schemaCollection): SchemaCollection;
}
