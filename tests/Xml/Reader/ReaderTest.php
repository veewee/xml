<?php

declare(strict_types=1);

namespace VeeWee\Xml\Tests\Reader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Reader\Reader;
use VeeWee\Xml\Tests\Helper\FillFileTrait;
use function Psl\Fun\identity;
use function VeeWee\Xml\Reader\Matcher\all;
use function VeeWee\Xml\Reader\Matcher\node_attribute;
use function VeeWee\Xml\Reader\Matcher\node_name;

class ReaderTest extends TestCase
{
    use FillFileTrait;

    /**
     * @test
     * @dataProvider provideXmlExpectations
     */
    public function it_can_provide_xml_string(string $xml, callable $matcher, array $expected): void
    {
        $reader = Reader::fromXmlString($xml, identity());
        $iterator = $reader->provide($matcher);

        self::assertSame($expected, [...$iterator]);
    }

    /**
     * @test
     * @dataProvider provideXmlExpectations
     */
    public function it_can_provide_xml_file(string $xml, callable $matcher, array $expected): void
    {
        [$file, $handle] = $this->fillFile($xml);

        $reader = Reader::fromXmlFile($file, identity());
        $iterator = $reader->provide($matcher);

        self::assertSame($expected, [...$iterator]);

        fclose($handle);
    }

    /** @test */
    public function it_throws_exception_on_invalid_xml_during_iteration(): void
    {
        $xml = <<<'EOXML'
            <root>
                <user>Jos</user>
                <user>Bos</user>
                <user>Mos</user>    
                <user />Mos</user>    
            </root>
        EOXML;

        $reader = Reader::fromXmlString($xml);
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
