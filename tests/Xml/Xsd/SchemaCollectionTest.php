<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xsd;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Xsd\Schema;
use VeeWee\Xml\Xsd\SchemaCollection;

final class SchemaCollectionTest extends TestCase
{
    public function test_it_is_a_list_of_schemas(): void
    {
        $schemas = new SchemaCollection(
            $schema1 = Schema::withoutNamespace('location1.xsd'),
            $schema2 = Schema::withoutNamespace('location2.xsd')
        );

        static::assertCount(2, $schemas);
        static::assertSame([$schema1, $schema2], [...$schemas]);
    }

    
    public function test_it_can_add_schemas_immutably(): void
    {
        $schemas = new SchemaCollection(
            $schema1 = Schema::withoutNamespace('location1.xsd'),
        );
        $new = $schemas->add($schema2 = Schema::withoutNamespace('location2.xsd'));

        static::assertNotSame($schemas, $new);
        static::assertSame([$schema1, $schema2], [...$new]);
    }
    
    
    public function test_it_can_manipulate_a_collection(): void
    {
        $schemas = new SchemaCollection(
            $schema1 = Schema::withoutNamespace('location1.xsd'),
            $schema2 = Schema::withoutNamespace('location2.xsd')
        );
        
        $new = $schemas->manipulate(
            static fn (SchemaCollection $old) => $old->filter(static fn (Schema $schema): bool => $schema === $schema1)
        );
        
        static::assertNotSame($schemas, $new);
        static::assertSame([$schema1], [...$new]);
    }

    
    public function test_it_can_filter_schemas(): void
    {
        $schemas = new SchemaCollection(
            $schema1 = Schema::withoutNamespace('location1.xsd'),
            $schema2 = Schema::withoutNamespace('location2.xsd')
        );

        $filtered = $schemas->filter(
            static fn (Schema $schema): bool => $schema === $schema1
        );

        static::assertNotSame($schemas, $filtered);
        static::assertSame([$schema1], [...$filtered]);
    }

    
    public function test_it_can_map_over_schemas(): void
    {
        $schemas = new SchemaCollection(
            $schema1 = Schema::withoutNamespace($loc1 = 'location1.xsd'),
            $schema2 = Schema::withoutNamespace($loc2 = 'location2.xsd')
        );

        $mapped = $schemas->map(
            static fn (Schema $schema): string => $schema->location()
        );

        static::assertSame([$loc1, $loc2], $mapped);
    }
}
