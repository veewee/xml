<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader;

use Generator;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use VeeWee\Xml\Reader\Node\Pointer;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\stop_on_first_issue;
use function VeeWee\Xml\Internal\configure;
use function VeeWee\Xml\Reader\Loader\xml_file_loader;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;

final class Reader
{
    /**
     * @var callable(): XMLReader $factory
     */
    private $factory;

    /**
     * @param callable(): XMLReader $factory
     */
    private function __construct(
        callable $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param callable(): XMLReader $loader
     * @param list<callable(XMLReader): XMLReader> $configurators
     */
    public static function configure(callable $loader, callable ... $configurators): self
    {
        return new self(static fn () => configure(...$configurators)($loader()));
    }

    /**
     * @param list<callable(XMLReader): XMLReader> $configurators
     */
    public static function fromXmlFile(string $file, callable ... $configurators): self
    {
        return self::configure(xml_file_loader($file), ...$configurators);
    }

    /**
     * @param list<callable(XMLReader): XMLReader> $configurators
     */
    public static function fromXmlString(string $xml, callable ... $configurators): self
    {
        return self::configure(xml_string_loader($xml), ...$configurators);
    }

    /**
     * @param callable(NodeSequence): bool $matcher
     *
     * @return Generator<MatchingNode>
     *
     * @throws RuntimeException
     */
    public function provide(callable $matcher, ?Signal $signal = null): Generator
    {
        $signal ??= new Signal();
        $reader = ($this->factory)();
        $pointer = Pointer::create();

        yield from stop_on_first_issue(
            static function () use ($reader, $signal): bool {
                if($signal->stopRequested()) {
                    return !$reader->close();
                }

                return $reader->read();
            },
            static function () use ($reader, $pointer, $matcher) : ?MatchingNode {
                if ($reader->nodeType === XMLReader::END_ELEMENT) {
                    $pointer->leaveElement();

                    return null;
                }

                if ($reader->nodeType === XMLReader::ELEMENT) {
                    $isEmptyElement = $reader->isEmptyElement;
                    $element = ElementNode::fromReader(
                        $reader,
                        $pointer->getNextSiblingPosition(),
                        static function () use ($reader): array {
                            $attributes = [];
                            while ($reader->moveToNextAttribute()) {
                                $attributes[] = AttributeNode::fromReader($reader);
                            }
                            return $attributes;
                        }
                    );

                    $pointer->enterElement($element);
                    $outerXml = $matcher($pointer->getNodeSequence()) ? $reader->readOuterXml() : null;

                    /** @psalm-suppress RiskyTruthyFalsyComparison */
                    $match = $outerXml ? new MatchingNode($outerXml, $pointer->getNodeSequence()) : null;

                    if ($isEmptyElement) {
                        $pointer->leaveElement();
                    }

                    return $match;
                }

                return null;
            }
        );
    }
}
