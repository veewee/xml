<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Locator\Element;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\Element\parent_element;

final class ParentElementTest extends TestCase
{
    public function test_it_can_detect_parents(): void
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <hello>
                <world>
                    <jos />
                    <bos />
                </world>
            </hello>
            EOXML
        );
        $domdoc = $doc->toUnsafeDocument();

        $jos = $domdoc->documentElement->firstElementChild->firstElementChild;

        $world = parent_element($jos);
        static::assertSame($world, $domdoc->documentElement->firstElementChild);

        $hello = parent_element($world);
        static::assertSame($hello, $domdoc->documentElement);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Can not find parent element for \DOM\Element hello');
        parent_element($hello);
    }
}
