<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer;

use Generator;
use VeeWee\Xml\Exception\RuntimeException;
use XMLWriter;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\Internal\configure;
use function VeeWee\Xml\Writer\Opener\xml_file_opener;
use function VeeWee\Xml\Writer\Opener\xml_stream_opener;

final class Writer
{
    private XMLWriter $writer;

    private function __construct(
        XMLWriter $writer,
        private readonly bool $inMemory = false,
    ) {
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
        return new self($configurators !== [] ? configure(...$configurators)($writer) : $writer);
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
        if ($configurators !== []) {
            // A configurator can replace or reopen the writer, so only an unconfigured one is known to stay in memory.
            return self::fromUnsafeWriter(XMLWriter::toMemory(), ...$configurators);
        }

        return new self(XMLWriter::toMemory(), true);
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
                    if ($written === false) {
                        throw RuntimeException::withMessage('Could not write the provided XML to the stream.');
                    }
                }
            }
        );

        // Flush the content to the file or stream.
        // An in-memory buffer can only be read through flush() or outputMemory(), which both flush on their own.
        if (!$this->inMemory) {
            $this->writer->flush(false);
        }

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
