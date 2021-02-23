<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use function Psl\Iter\first_key;

function root(array $data): callable
{
    $rootName = first_key($data);
    $element = $data[$rootName];

    return is_node_list($element)
        ? children($rootName, $element)
        : element($rootName, $element);
}
