<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use function Psl\Type\dict;
use function Psl\Type\int;
use function Psl\Type\mixed;

/**
 * @psalm-internal VeeWee\Xml\Encoding
 */
function is_node_list(array $data): bool
{
    return dict(int(), mixed())->matches($data);
}
