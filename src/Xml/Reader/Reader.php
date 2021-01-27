<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader;

use Generator;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use XMLReader;
use function Psl\Fun\pipe;
use function VeeWee\Xml\ErrorHandling\stop_on_first_issue;
use function VeeWee\Xml\Reader\Loader\xml_file_loader;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;

final class Reader
{
    /**
     * @var callable(): XMLReader
     */
    private $factory;

    /**
     * @param callable(): XMLReader $factory
     */
    private function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    public static function configure(callable $loader, callable ... $configurators): self
    {
        return new self(static fn () => pipe(...$configurators)($loader()));
    }
    
    public static function fromXmlFile(string $file, callable ... $configurators): self
    {
        return self::configure(xml_file_loader($file), ...$configurators);
    }

    public static function fromXmlString(string $xml, callable ... $configurators): self
    {
        return self::configure(xml_string_loader($xml), ...$configurators);
    }

    /**
     * @param callable(): bool $matcher
     *
     * @return Generator<string>
     */
    public function provide(callable $matcher): Generator
    {
        $reader = ($this->factory)();
        $sequence = new NodeSequence();

        yield from stop_on_first_issue(
            static fn(): bool => $reader->read(),
            static function () use ($matcher) : ?string {
                // $reader->name === 'user' ? $reader->readOuterXml() : null,

            }
        );
    }

    private function oldversion(XMLReader $reader, NodeSequence $sequence, callable $matcher): ?string
    {
        $sequence ??= new NodeSequence();
        $depth = 0;
        $siblingCount = [];

        while($reader->read()){
            switch ($reader->nodeType) {
                case XMLReader::END_ELEMENT:
                    unset($siblingCount[$depth]);
                    $depth--;
                    $sequence = $sequence->pop();
                    break;
                case XMLReader::ELEMENT:
                    $siblingsCount[$depth] = isset($siblingsCount[$depth]) ? ($siblingsCount[$depth]+1) : 1;
                    $position = $siblingsCount[$depth];
                    $depth++;

                    $element = new ElementNode();
                    $element->position = $position;
                    $element->name = $reader->name;
                    $element->localName = $reader->localName;
                    $element->namespace = $reader->namespaceURI;
                    $element->namespaceAlias = $reader->prefix;

                    while($reader->moveToNextAttribute()) {
                        $attribute = new AttributeNode();
                        $attribute->name = $reader->name;
                        $attribute->localName = $reader->localName;
                        $attribute->namespaceAlias = $reader->prefix;
                        $attribute->namespace = $reader->namespaceURI;
                        $attribute->value = $reader->value;
                        $element->arguments[] = $attribute;
                    }

                    $sequence = $sequence->append($element);
                    yield from $this->runMatchers(OpeningElementMatcher::class, $sequence, $reader);
                    break;
            }
        }
    }
}
