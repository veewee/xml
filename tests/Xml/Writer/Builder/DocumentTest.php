<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Builder;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Util\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\document;
use function VeeWee\Xml\Writer\Builder\element;

final class DocumentTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_create_a_document(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                document(
                    '1.0',
                    'UTF-8',
                    element('hello')
                )
            );
        });

        static::assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $result);
        static::assertXmlStringEqualsXmlString('<hello />', $result);
    }
}
