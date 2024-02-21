<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Node;

use \DOM\Element;
use InvalidArgumentException;
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

    
    public function test_it_throws_exception_on_unlinked_node(): void
    {
        $element = new \DOM\Element('name');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected to find an ownerDocument on provided \DOM\Node.');

        detect_document($element);
    }
}
