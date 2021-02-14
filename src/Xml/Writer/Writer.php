<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer;

use VeeWee\Xml\Exception\RuntimeException;
use XMLWriter;
use function Psl\Fun\pipe;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

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
        return new self(pipe(...$configurators)(new XMLWriter()));
    }

    /**
     * @param string $file
     * @param list<(callable(XMLWriter): XMLWriter)> $configurators
     *
     * @return self
     */
    public static function forFile(string $file, callable ... $configurators): self
    {
        return self::configure(
            static fn (XMLWriter $writer): XMLWriter  => disallow_issues(
                static function () use ($writer, $file): XMLWriter {
                    disallow_libxml_false_returns(
                        $writer->openUri($file),
                        'Could not write to file %s'.$file
                    );

                    return $writer;
                }
            ),
            ...$configurators
        );
    }

    /**
     * @param callable(XMLWriter): \Generator<bool> $writer
     * @throws RuntimeException
     */
    public function write(callable $writer): void
    {
        $xmlWriter = $this->writer;
        $cursor = $writer($xmlWriter);
        $flush = static function() use ($xmlWriter): void {
            $xmlWriter->flush(true);
        };

        disallow_issues(
            static function () use ($cursor, $flush) : void {
                foreach ($cursor as $written) {
                    disallow_libxml_false_returns(
                        $written,
                        'Could not write the provided XML to the stream.'
                    );

                    // TODO: Do we really need to manually flush and if so : should we do it on every write?
                    $flush();
                }
            }
        );
    }
}
