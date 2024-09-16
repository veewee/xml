<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use \DOM\XMLDocument;
use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\format_output;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class FormatOutputTest extends TestCase
{
    /**
     * @dataProvider provideFormatOutputCases
     */
    public function test_it_can_trim_contents(
        ?bool $formatOutput,
        string $input,
        string $expected,
        ?int $flags
    ): void
    {
        $configurator = $formatOutput === null ? format_output() : format_output($formatOutput);

        $doc = Document::fromLoader(
            xml_string_loader($input, $flags ?? 0)
        )->toUnsafeDocument();
        $result = $configurator($doc);

        static::assertSame($formatOutput ?? true, $result->formatOutput);
        static::assertSame($expected, xml_string()($result->documentElement));
    }

    public static function provideFormatOutputCases()
    {
        yield 'empty-arg' => [
            'formatOutput' => null,
            'input' => '<hello><world/></hello>',
            'expected' => <<<EOXML
            <hello>
              <world/>
            </hello>
            EOXML,
            'flags' => null,
        ];
        yield 'format-output' => [
            'formatOutput' => true,
            'input' => '<hello><world/></hello>',
            'expected' => <<<EOXML
            <hello>
              <world/>
            </hello>
            EOXML,
            'flags' => null,
        ];
        yield 'dont-format-output' => [
            'formatOutput' => false,
            'input' => '<hello><world/></hello>',
            'expected' => <<<EOXML
            <hello><world/></hello>
            EOXML,
            'flags' => null,
        ];
        yield 'with-blanks' => [
            'formatOutput' => true,
            'input' => '<hello>    <world/>    </hello>',
            'expected' => <<<EOXML
            <hello>    <world/>    </hello>
            EOXML,
            'flags' => null,
        ];
        yield 'with-stripped-blanks' => [
            'formatOutput' => true,
            'input' => '<hello>    <world/>    </hello>',
            'expected' => <<<EOXML
            <hello>
              <world/>
            </hello>
            EOXML,
            'flags' => LIBXML_NOBLANKS,
        ];
    }
}
