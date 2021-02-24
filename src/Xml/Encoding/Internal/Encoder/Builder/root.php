<?php

declare(strict_types=1);

namespace VeeWee\Xml\Encoding\Internal\Encoder\Builder;

use DOMDocument;
use DOMNode;
use VeeWee\Xml\Encoding\Exception\EncodingException;
use function Psl\Dict\map_with_key;
use function VeeWee\Xml\Dom\Builder\element as elementBuilder;
use function VeeWee\Xml\Dom\Builder\nodes;
use function VeeWee\Xml\Dom\Builder\value;

/**
 * @param array<string, string|array> $data
 * @return callable(DOMDocument): list<DOMNode>
 *
 * @throws EncodingException
 */
function root(array $data): callable
{
    if (is_node_list($data)) {
        throw EncodingException::invalidRoot('list');
    }

    return nodes(
        ...map_with_key(
            $data,
            static fn (string $key, array|string $value): callable
                => is_array($value) ? element($key, $value) : elementBuilder($key, value($value))
        )
    );
}
