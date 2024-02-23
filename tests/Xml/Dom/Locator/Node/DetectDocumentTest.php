<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Node;

use \DOM\Element;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

final class DetectDocumentTest extends TestCase
{
    public function test_it_can_detect_document(): void
    {
        $doc = Document::fromXmlString('<hello />');
        $domdoc = $doc->toUnsafeDocument();

        static::assertSame($domdoc, detect_document($domdoc));
        static::assertSame($domdoc, detect_document($domdoc->documentElement));
    }
}
