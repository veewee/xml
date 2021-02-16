<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Xpath\Configurator;

use PHPUnit\Framework\TestCase;
use Psl\Type;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Xpath;

final class AllFunctionsTest extends TestCase
{
    public function test_it_can_evaluate_with_php_function(): void
    {
        $doc = Document::fromXmlString(
            $xml = '<hello><item>Jos</item></hello>'
        );
        $xpath = Xpath::fromDocument($doc, Xpath\Configurator\all_functions());

        $result = $xpath->evaluate('php:functionString("str_replace", "J", "B", string(//item))', Type\string());
        static::assertSame($result, 'Bos');
    }
}
