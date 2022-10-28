<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer;

use Generator;
use VeeWee\Xml\Exception\RuntimeException;
use XMLWriter;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;
use function VeeWee\Xml\Util\configure;
use function VeeWee\Xml\Writer\Configurator\open;
use function VeeWee\Xml\Writer\Opener\xml_file_opener;

final class Writer
{
    private XMLWriter $writer;

    private function __construct(XMLWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     */
    public static function configure(callable ... $configurators): self
    {
        return self::fromUnsafeWriter(new XMLWriter(), ...$configurators);
    }

    /**
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     */
    public static function fromUnsafeWriter(XMLWriter $writer, callable ... $configurators): self
    {
        return new self(configure(...$configurators)($writer));
    }

    /**
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     *
     */
    public static function forFile(string $file, callable ... $configurators): self
    {
        return self::configure(
            open(xml_file_opener($file)),
            ...$configurators
        );
    }

    /**
     * @param callable(XMLWriter): Generator<bool> $writer
     * @throws RuntimeException
     */
    public function write(callable $writer): void
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
    }
}
