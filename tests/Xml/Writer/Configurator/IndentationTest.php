<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Writer\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Writer\Util\UseInMemoryWriterTrait;
use VeeWee\Xml\Writer\Writer;
use XMLWriter;
use function VeeWee\Xml\Writer\Builder\children;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\value;
use function VeeWee\Xml\Writer\Configurator\indentation;

final class IndentationTest extends TestCase
{
    use UseInMemoryWriterTrait;

    
    public function test_it_can_indent(): void
    {
        $result = $this->runInMemory(static function (XMLWriter $xmlWriter): void {
            $writer = Writer::fromUnsafeWriter($xmlWriter, indentation('    '));
            $writer->write(
                element('root', children([
                    element('hello', value('world'))
                ]))
            );
        });

        static::assertXmlStringEqualsXmlString(
            <<<EOXML
            <root>
                <hello>world</hello>
            </root>    
            EOXML,
            $result
        );
    }
}
