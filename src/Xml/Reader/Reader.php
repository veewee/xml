<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader;

use Closure;
use Generator;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\Node\AttributeNode;
use VeeWee\Xml\Reader\Node\ElementNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use VeeWee\Xml\Reader\Node\Pointer;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\stop_on_first_issue;
use function VeeWee\Xml\Reader\Loader\xml_file_loader;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;
use function VeeWee\Xml\Util\configure;

final class Reader
{
    /**
     * @var \Closure(): XMLReader $factory
     */
    private $factory;

    /**
     * @param \Closure(): XMLReader $factory
     */
    private function __construct(
        Closure $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param \Closure(): XMLReader $loader
     * @param list<\Closure(XMLReader): XMLReader> $configurators
     */
    public static function configure(Closure $loader, Closure ... $configurators): self
    {
        return new self(static fn () => configure(...$configurators)($loader()));
    }

    /**
     * @param list<\Closure(XMLReader): XMLReader> $configurators
     */
    public static function fromXmlFile(string $file, Closure ... $configurators): self
    {
        return self::configure(xml_file_loader($file), ...$configurators);
    }

    /**
     * @param list<\Closure(XMLReader): XMLReader> $configurators
     */
    public static function fromXmlString(string $xml, Closure ... $configurators): self
    {
        return self::configure(xml_string_loader($xml), ...$configurators);
    }

    /**
     * @param \Closure(NodeSequence): bool $matcher
     *
     * @return Generator<string>
     *
     * @throws RuntimeException
     */
    public function provide(Closure $matcher): Generator
    {
        $reader = ($this->factory)();
        $pointer = Pointer::create();

        yield from stop_on_first_issue(
            static fn (): bool => $reader->read(),
            static function () use ($reader, $pointer, $matcher) : ?string {
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
                    $result = $matcher($pointer->getNodeSequence()) ? $reader->readOuterXml() : null;

                    if ($isEmptyElement) {
                        $pointer->leaveElement();
                    }

                    return $result;
                }

                return null;
            }
        );
    }
}
