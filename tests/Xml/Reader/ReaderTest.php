<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\Node\NodeSequence;
use VeeWee\Xml\Reader\Reader;
use VeeWee\Xml\Tests\Helper\FillFileTrait;
use function Psl\Fun\identity;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;
use function VeeWee\Xml\Reader\Matcher\all;
use function VeeWee\Xml\Reader\Matcher\node_attribute;
use function VeeWee\Xml\Reader\Matcher\node_name;

final class ReaderTest extends TestCase
{
    use FillFileTrait;

    /**
     * @dataProvider provideXmlExpectations
     */
    public function test_it_can_provide_xml_string(string $xml, callable $matcher, array $expected): void
    {
        $reader = Reader::fromXmlString($xml, identity());
        $iterator = $reader->provide($matcher);

        static::assertSame($expected, [...$iterator]);
    }

    /**
     * @dataProvider provideXmlExpectations
     */
    public function test_it_can_provide_xml_file(string $xml, callable $matcher, array $expected): void
    {
        [$file, $handle] = $this->fillFile($xml);

        $reader = Reader::fromXmlFile($file, identity());
        $iterator = $reader->provide($matcher);

        static::assertSame($expected, [...$iterator]);

        fclose($handle);
    }

    public function test_it_throws_exception_on_invalid_xml_during_iteration(): void
    {
        $xml = <<<'EOXML'
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>    
                <user />Mos</user>    
            </root>
        EOXML;

        $reader = Reader::configure(xml_string_loader($xml));
        $iterator = $reader->provide(static fn () => true);

        $this->expectException(RuntimeException::class);
        $this->expectErrorMessage('Detected issues during the parsing of the XML Stream');
        [...$iterator];
    }

    public function provideXmlExpectations()
    {
        yield 'simple' => [
            <<<'EOXML'
                <root>
                    <user>Jos</user>
                    <user>Bos</user>
                    <user>Mos</user>    
                </root>
            EOXML,
            node_name('user'),
            [
                '<user>Jos</user>',
                '<user>Bos</user>',
                '<user>Mos</user>',
            ]
        ];

        yield 'attributes' => [
            <<<'EOXML'
                <root>
                    <user locale="nl">Jos</user>
                    <user locale="nl">Bos</user>
                    <user locale="en">Mos</user>
                </root>
            EOXML,
            all(
                node_name('user'),
                node_attribute('locale', 'nl')
            ),
            [
                '<user locale="nl">Jos</user>',
                '<user locale="nl">Bos</user>',
            ]
        ];

        yield 'multi-attributes' => [
            <<<'EOXML'
                <root>
                    <user locale="nl" dialect="kempisch">Jos</user>
                    <user locale="nl" dialect="wvl">Bos</user>
                    <user locale="en">Mos</user>
                    <user locale="en">Mos</user>
                </root>
            EOXML,
            all(
                node_name('user'),
                node_attribute('locale', 'nl'),
                node_attribute('dialect', 'kempisch'),
            ),
            [
                '<user locale="nl" dialect="kempisch">Jos</user>',
            ]
        ];

        yield 'positioned' => [
            <<<'EOXML'
                <root>
                    <user>Jos</user>
                    <user>Bos</user>
                    <user>Mos</user>    
                    <user>Dos</user>    
                </root>
            EOXML,
            all(
                node_name('user'),
                static fn (NodeSequence $sequence): bool => ($sequence->current()->position() % 2 === 0),
            ),
            [
                '<user>Bos</user>',
                '<user>Dos</user>',
            ]
        ];

        yield 'nested' => [
            <<<'EOXML'
                <root>
                    <item>
                        <id>1</id>
                        <item>subitem1</item>
                    </item>
                    <item>
                        <id>2</id>
                        <item>subitem2</item>
                    </item>
                </root>
            EOXML,
            all(
                node_name('item'),
            ),
            [
                '<item>
            <id>1</id>
            <item>subitem1</item>
        </item>',
                '<item>subitem1</item>',
                '<item>
            <id>2</id>
            <item>subitem2</item>
        </item>',
                '<item>subitem2</item>',
            ]
        ];
    }
}
