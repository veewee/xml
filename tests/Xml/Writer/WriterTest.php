<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\TmpFileTrait;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function Psl\Fun\identity;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\raw;
use function VeeWee\Xml\Writer\Configurator\open;
use function VeeWee\Xml\Writer\Mapper\memory_output;
use function VeeWee\Xml\Writer\Opener\xml_file_opener;

final class WriterTest extends TestCase
{
    use UseInMemoryWriterTrait;
    use TmpFileTrait;


    public function test_it_can_open_a_file(): void
    {
        $this->createTmpFile(static function (string $path): void {
            $writer = Writer::forFile($path, identity());
            $writer->write(element('root'));

            self::assertXmlStringEqualsXmlFile($path, '<root />');
        });
    }

    public function test_it_can_open_in_memory(): void
    {
        $actual = Writer::inMemory()->write(raw('hello'))->map(memory_output());

        static::assertSame('hello', $actual);
    }

    public function test_it_can_configure_a_writer(): void
    {
        $this->createTmpFile(static function (string $path): void {
            $writer = Writer::configure(open(xml_file_opener($path)), identity());
            $writer->write(element('root'));

            self::assertXmlStringEqualsXmlFile($path, '<root />');
        });
    }


    public function test_it_can_fluently_write_multiple_times(): void
    {
        $this->createTmpFile(static function (string $path): void {
            $writer = Writer::forFile($path);
            $writer
                ->write(element('root1'))
                ->write(element('root2'))
                ->write(element('root3'));

            self::assertStringEqualsFile($path, '<root1/><root2/><root3/>');
        });
    }


    public function test_it_can_use_an_unsafe_writer(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                element('root')
            );
        });

        static::assertXmlStringEqualsXmlString($result, '<root />');
    }

    public function test_it_throws_error_on_not_initialized(): void
    {
        $this->expectExceptionMessage('Invalid or uninitialized XMLWriter object');

        $emptyWriter = Writer::configure();
        $emptyWriter->write(element('root'));
    }

    public function test_it_throws_error_on_invalid_write(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not write the provided XML to the stream.');

        $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter);
            $writer->write(
                static function () {
                    yield false;
                }
            );
        });
    }

    
    public function test_it_can_map_writer_to_something_else(): void
    {
        $writer = Writer::inMemory();
        $writer->write(raw('hello'));

        $actual = $writer->map(static fn (XMLWriter $writer) => $writer->outputMemory());

        static::assertSame('hello', $actual);
    }

    
    public function test_it_can_fluently_apply_callbacks(): void
    {
        $writer = Writer::inMemory();
        $writer->apply(static function (XMLWriter $writer) {
            $writer->writeRaw('hello');
        });

        $actual = $writer->map(static fn (XMLWriter $writer) => $writer->outputMemory());
        static::assertSame('hello', $actual);
    }
}
