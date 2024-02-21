<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\CdataSection;
use \DOM\Node;
use function VeeWee\Xml\Dom\Assert\assert_cdata;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(\DOM\CdataSection): \DOM\CdataSection> $configurators
 *
 * @return Closure(\DOM\Node): \DOM\CdataSection
 */
function cdata(string $data, ...$configurators): Closure
{
    return static function (\DOM\Node $node) use ($data, $configurators): \DOM\CdataSection {
        $document = detect_document($node);

        return assert_cdata(
            configure(...$configurators)($document->createCDATASection($data))
        );
    };
}
