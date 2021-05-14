<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xmlns;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Xmlns\Xmlns;

final class XmlnsTest extends TestCase
{
    /**
     *
     * @dataProvider provideKnownXmlnses
     */
    public function test_it_knows_some_xmlnses(Xmlns $xmlns, string $uri): void
    {
        static::assertSame($xmlns->value(), $uri);
        static::assertTrue($xmlns->matches(Xmlns::load($uri)));
    }

    public function provideKnownXmlnses()
    {
        yield 'xml' => [
            Xmlns::xml(),
            'http://www.w3.org/XML/1998/namespace'
        ];
        yield 'xsi' => [
            Xmlns::xsi(),
            'http://www.w3.org/2001/XMLSchema-instance'
        ];
        yield 'phpXpath' => [
            Xmlns::phpXpath(),
            'http://php.net/xpath'
        ];
        yield 'xmlns' => [
            Xmlns::xmlns(),
            'http://www.w3.org/2000/xmlns/'
        ];
    }
}
