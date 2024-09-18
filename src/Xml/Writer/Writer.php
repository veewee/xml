<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer;

use Generator;
use VeeWee\Xml\Exception\RuntimeException;
use XMLWriter;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;
use function VeeWee\Xml\Internal\configure;
use function VeeWee\Xml\Writer\Opener\memory_opener;
use function VeeWee\Xml\Writer\Opener\xml_file_opener;
use function VeeWee\Xml\Writer\Opener\xml_stream_opener;

final class Writer
{
    private XMLWriter $writer;

    private function __construct(XMLWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param callable(): XMLWriter $opener
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     */
    public static function configure(callable $opener, callable ... $configurators): self
    {
        return self::fromUnsafeWriter($opener(), ...$configurators);
    }

    /**
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     */
    public static function fromUnsafeWriter(XMLWriter $writer, callable ... $configurators): self
    {
        return new self(configure(...$configurators)($writer));
    }

    /**
     * @param non-empty-string $file
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     */
    public static function forFile(string $file, callable ... $configurators): self
    {
        return self::configure(
            xml_file_opener($file),
            ...$configurators
        );
    }

    /**
     * @param resource $stream
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     */
    public static function forStream(mixed $stream, callable ... $configurators): self
    {
        return self::configure(
            xml_stream_opener($stream),
            ...$configurators
        );
    }

    /**
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     */
    public static function inMemory(callable ... $configurators): self
    {
        return self::configure(
            memory_opener(),
            ...$configurators
        );
    }

    /**
     * @param callable(XMLWriter): Generator<bool> $writer
     * @throws RuntimeException
     */
    public function write(callable $writer): self
    {
        $xmlWriter = $this->writer;
        $cursor = $writer($xmlWriter);

        disallow_issues(
            static function () use ($cursor) : void {
                foreach ($cursor as $written) {
                    disallow_libxml_false_returns(
                        $written,
                        'Could not write the provided XML to the stream.'
                    );
                }
            }
        );

        // Flush the content to the file.
        // Make sure to keep the buffer in case you are using an in-memory writer.
        $this->writer->flush(false);

        return $this;
    }

    /**
     * @param callable(XMLWriter): mixed $applicative
     */
    public function apply(callable $applicative): self
    {
        $applicative($this->writer);

        return $this;
    }

    /**
     * @template T
     * @param callable(XMLWriter): T $mapper
     *
     * @return T
     */
    public function map(callable $mapper)
    {
        return $mapper($this->writer);
    }
}
