<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xsd\Manipulator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Xsd\Schema;
use VeeWee\Xml\Xsd\SchemaCollection;
use function VeeWee\Xml\Xsd\Manipulator\base_path;

final class BasePathTest extends TestCase
{
    public function test_it_can_add_base_path_to_schema_items(): void
    {
        $schemas = new SchemaCollection(
            Schema::withoutNamespace('location.xsd'),
            Schema::withoutNamespace('./location.xsd'),
            Schema::withoutNamespace('../location.xsd'),
            Schema::withoutNamespace('path/location.xsd'),
            Schema::withoutNamespace('/location.xsd'),
            Schema::withoutNamespace('/var/path/location.xsd'),
            Schema::withoutNamespace('c:/var/path/location.xsd'),
            Schema::withoutNamespace('https://www.com/location.xsd'),
            Schema::withoutNamespace('http://www.com/location.xsd'),
            Schema::withoutNamespace('file://www.com/location.xsd'),
            Schema::withoutNamespace('data://www.com/location.xsd'),
        );
        $manipulator = base_path('/var/ww/');

        $result = $schemas->manipulate($manipulator)->map(
            static fn (Schema $schema): string => $schema->location()
        );

        static::assertSame(
            [
                '/var/ww/location.xsd',
                '/var/ww/./location.xsd',
                '/var/ww/../location.xsd',
                '/var/ww/path/location.xsd',
                '/location.xsd',
                '/var/path/location.xsd',
                'c:/var/path/location.xsd',
                'https://www.com/location.xsd',
                'http://www.com/location.xsd',
                'file://www.com/location.xsd',
                'data://www.com/location.xsd',
            ],
            $result
        );
    }
}
