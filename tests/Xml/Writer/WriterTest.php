<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer;

use LogicException;
use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Tests\Xml\Helper\TmpFileTrait;
use VeeWee\Tests\Xml\Writer\Helper\UseInMemoryWriterTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function Psl\Fun\identity;
use function VeeWee\Xml\Writer\Applicative\flush;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\raw;
use function VeeWee\Xml\Writer\Mapper\memory_output;
use function VeeWee\Xml\Writer\Opener\xml_file_opener;

final class WriterTest extends TestCase
{
    use UseInMemoryWriterTrait;
    use TmpFileTrait;
    use FillFileTrait;


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

    public function test_it_can_open_a_stream(): void
    {
        [$_, $handle] = $this->fillFile('');
        Writer::forStream($handle)->write(raw('hello'))->apply(flush());
        rewind($handle);

        static::assertSame('hello', stream_get_contents($handle));
    }

    public function test_it_flushes_a_stream_after_every_write(): void
    {
        [$_, $handle] = $this->fillFile('');
        $writer = Writer::forStream($handle);

        $writer->write(raw('hello'));
        rewind($handle);
        static::assertSame('hello', stream_get_contents($handle));

        $writer->write(raw(' world'));
        rewind($handle);
        static::assertSame('hello world', stream_get_contents($handle));
    }

    public function test_it_flushes_a_stream_returned_by_an_in_memory_configurator(): void
    {
        [$_, $handle] = $this->fillFile('');

        $writer = Writer::inMemory(
            static fn (XMLWriter $writer): XMLWriter => XMLWriter::toStream($handle),
        );

        $writer->write(raw('hello'));
        rewind($handle);

        static::assertSame('hello', stream_get_contents($handle));
    }

    public function test_it_flushes_an_in_memory_writer_that_a_configurator_reopened(): void
    {
        $this->createTmpFile(static function (string $path): void {
            $writer = Writer::inMemory(
                static function (XMLWriter $writer) use ($path): XMLWriter {
                    $writer->openUri($path);
                    return $writer;
                },
            );

            $writer->write(element('root'));

            self::assertXmlStringEqualsXmlFile($path, '<root />');
        });
    }

    public function test_it_can_configure_a_writer(): void
    {
        $this->createTmpFile(static function (string $path): void {
            $writer = Writer::configure(xml_file_opener($path), identity());
            $writer->write(element('root'));

            self::assertXmlStringEqualsXmlFile($path, '<root />');
        });
    }


    public function test_it_applies_configurators_in_order(): void
    {
        $actual = Writer::inMemory(
            static function (XMLWriter $writer): XMLWriter {
                $writer->writeRaw('a');
                return $writer;
            },
            static function (XMLWriter $writer): XMLWriter {
                $writer->writeRaw('b');
                return $writer;
            },
        )->write(raw('c'))->map(memory_output());

        static::assertSame('abc', $actual);
    }

    public function test_it_uses_the_writer_returned_by_a_configurator(): void
    {
        $replacement = XMLWriter::toMemory();
        $replacement->writeRaw('replaced-');

        $actual = Writer::inMemory(
            static fn (XMLWriter $writer): XMLWriter => $replacement,
        )->write(raw('hello'))->map(memory_output());

        static::assertSame('replaced-hello', $actual);
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

    public function test_it_can_configure_an_unsafe_writer(): void
    {
        $actual = Writer::fromUnsafeWriter(
            XMLWriter::toMemory(),
            static function (XMLWriter $writer): XMLWriter {
                $writer->writeRaw('configured-');
                return $writer;
            },
        )->write(raw('hello'))->map(memory_output());

        static::assertSame('configured-hello', $actual);
    }

    public function test_it_throws_error_on_not_initialized(): void
    {
        $this->expectExceptionMessage('Invalid or uninitialized XMLWriter object');

        $emptyWriter = Writer::fromUnsafeWriter(new XMLWriter());
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

    public function test_it_wraps_the_invalid_write_exception_and_stops_writing(): void
    {
        $writer = Writer::inMemory();

        try {
            $writer->write(
                static function () {
                    yield true;
                    yield false;
                    throw new LogicException('The writer should stop after the first false.');
                }
            );
            static::fail('Expected an exception');
        } catch (RuntimeException $exception) {
            static::assertSame('Could not write the provided XML to the stream.' . PHP_EOL, $exception->getMessage());
            $previous = $exception->getPrevious();
            static::assertInstanceOf(RuntimeException::class, $previous);
            static::assertSame('Could not write the provided XML to the stream.', $previous->getMessage());
            static::assertNull($previous->getPrevious());
        }
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
