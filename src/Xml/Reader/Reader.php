<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader;

use Generator;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\Pointer;
use VeeWee\Xml\Reader\Node\NodeSequence;
use XMLReader;
use function Psl\Fun\pipe;
use function VeeWee\Xml\ErrorHandling\stop_on_first_issue;
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
        return new self(static fn () => pipe(...$configurators)($loader()));
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
     * @return Generator<string>
     */
    public function provide(callable $matcher): Generator
    {
        $reader = ($this->factory)();
        $pointer = Pointer::create();

        yield from stop_on_first_issue(
            static fn(): bool => $reader->read(),
            static function () use ($reader, $pointer, $matcher) : ?string {
                switch ($reader->nodeType) {
                    case XMLReader::END_ELEMENT:
                        $pointer->leaveElement();
                        break;
                    case XMLReader::ELEMENT:
                        $element = ElementNode::fromReader(
                            $reader,
                            $pointer->getCurrentSiblingPosition() + 1,
                            static function () use ($reader): array {
                                $attributes = [];
                                while($reader->moveToNextAttribute()) {
                                    $attributes[] = AttributeNode::fromReader($reader);
                                }
                                return $attributes;
                            }
                        );

                        $pointer->enterElement($element);

                        return $matcher($pointer->getNodeSequence()) ? $reader->readOuterXml() : null;
                }

                return null;
            }
        );
    }
}
