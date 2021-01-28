<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader;

use Generator;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\Pointer;
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
        $pointer = Pointer::create();

        yield from stop_on_first_issue(
            static fn(): bool => $reader->read(),
            static function () use ($reader, $pointer, $matcher) : ?string {
                switch ($reader->nodeType) {
                    case XMLReader::END_ELEMENT:
                        $pointer->leaveElement();
                        break;
                    case XMLReader::ELEMENT:
                        $element = new ElementNode();
                        $element->position = 1; // TODO : load or set from pointer!
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
                            $element->attributes[] = $attribute;
                        }

                        $pointer->enterElement($element);

                        return $matcher($pointer->getNodeSequence()) ? $reader->readOuterXml() : null;
                }

                return null;
            }
        );
    }
}
