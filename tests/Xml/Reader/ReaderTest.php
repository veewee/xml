<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader;

use PHPUnit\Framework\TestCase;
use VeeWee\Tests\Xml\Helper\FillFileTrait;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\MatchingNode;
use VeeWee\Xml\Reader\Node\NodeSequence;
use VeeWee\Xml\Reader\Reader;
use VeeWee\Xml\Reader\Signal;
use function Psl\Fun\identity;
use function Psl\Vec\map;
use function VeeWee\Xml\Reader\Loader\xml_string_loader;
use function VeeWee\Xml\Reader\Matcher\all;
use function VeeWee\Xml\Reader\Matcher\attribute_value;
use function VeeWee\Xml\Reader\Matcher\element_name;

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

        static::assertSame($expected, map($iterator, static fn (MatchingNode $match): string => $match->xml()));
    }

    /**
     * @dataProvider provideXmlExpectations
     */
    public function test_it_can_provide_xml_file(string $xml, callable $matcher, array $expected): void
    {
        [$file, $handle] = $this->fillFile($xml);

        $reader = Reader::fromXmlFile($file, identity());
        $iterator = $reader->provide($matcher);

        static::assertSame($expected, map($iterator, static fn (MatchingNode $match): string => $match->xml()));

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
        $this->expectExceptionMessage('Detected issues during the parsing of the XML Stream');
        [...$iterator];
    }


    public function test_it_can_send_stop_signal(): void
    {
        $xml = <<<'EOXML'
                <root>
                    <user>Jos</user>
                    <user>Bos</user>
                    <user>Mos</user>    
                </root>
        EOXML;

        $reader = Reader::fromXmlString($xml);
        $signal = new Signal();

        $actual = [];
        foreach ($reader->provide(element_name('user'), $signal) as $match) {
            $actual[] = $match->xml();
            $signal->stop();
        }

        static::assertSame(['<user>Jos</user>'], $actual);
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
            element_name('user'),
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
                element_name('user'),
                attribute_value('locale', 'nl')
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
                element_name('user'),
                attribute_value('locale', 'nl'),
                attribute_value('dialect', 'kempisch'),
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
                element_name('user'),
                static fn (NodeSequence $sequence): bool => ($sequence->current()->position() % 2 === 0),
            ),
            [
                '<user>Bos</user>',
                '<user>Dos</user>',
            ]
        ];

        yield 'self-closing-with-position-check' => [
            <<<'EOXML'
                <root>
                    <user name="Jos" />
                    <user name="Bos" />
                    <user name="Mos" />
                    <foo>Bar</foo>
                    <user />
                    <user name="Dos" />
                </root>
            EOXML,
            all(
                element_name('user'),
                static fn (NodeSequence $sequence): bool => ($sequence->current()->position() % 2 === 0),
            ),
            [
                '<user name="Bos"/>',
                '<user name="Dos"/>',
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
                element_name('item'),
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
