<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use Dom\CDATASection;
use Dom\Node;
use function VeeWee\Xml\Dom\Assert\assert_cdata;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(CDATASection): CDATASection> $configurators
 *
 * @return Closure(Node): CDATASection
 */
function cdata(string $data, ...$configurators): Closure
{
    return static function (Node $node) use ($data, $configurators): CDATASection {
        $document = detect_document($node);

        return assert_cdata(
            configure(...$configurators)($document->createCDATASection($data))
        );
    };
}
