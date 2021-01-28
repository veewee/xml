<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

final class ElementNode
{
    public int $position;
    public $name;
    public $localName;
    public $namespace;
    public $namespaceAlias;
    public $attributes = [];
}
