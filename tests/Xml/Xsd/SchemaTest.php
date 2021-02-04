<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xsd;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Xsd\Schema;

final class SchemaTest extends TestCase
{
    public function test_it_can_have_a_namespace(): void
    {
        $schema = Schema::withNamespace(
            $namespace = 'http://namespace',
            $location = 'location.xsd'
        );

        static::assertSame($namespace, $schema->namespace());
        static::assertSame($location, $schema->location());
    }

    
    public function test_it_can_have_no_namespace(): void
    {
        $schema = Schema::withoutNamespace(
            $location = 'location.xsd'
        );

        static::assertSame(null, $schema->namespace());
        static::assertSame($location, $schema->location());
    }

    
    public function test_it_can_swap_locations_whilst_keeping_namespaced_immutably(): void
    {
        $schema = Schema::withNamespace($namespace = 'http://namespace', $location = 'location.xsd');
        $new = $schema->withLocation($location2 = 'location2.xsd');

        static::assertNotSame($schema, $new);
        static::assertSame($namespace, $new->namespace());
        static::assertSame($location2, $new->location());
    }

    
    public function test_it_can_swap_locations_without_namespaced_immutably(): void
    {
        $schema = Schema::withoutNamespace($location = 'location.xsd');
        $new = $schema->withLocation($location2 = 'location2.xsd');

        static::assertNotSame($schema, $new);
        static::assertSame(null, $new->namespace());
        static::assertSame($location2, $new->location());
    }
}
