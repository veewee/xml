<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Xsd\Manipulator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Xsd\Schema;
use VeeWee\Xml\Xsd\SchemaCollection;
use function VeeWee\Xml\Xsd\Manipulator\overwrite_with_local_files;

final class OverwriteWithLocalFilesTest extends TestCase
{
    public function test_it_can_replace_namespaced_xsds_with_local_path(): void
    {
        $schemas = new SchemaCollection(
            Schema::withoutNamespace('location.xsd'),
            Schema::withNamespace(
                'http://www.w3.org/2001/XMLSchema',
                'https://www.w3.org/2009/XMLSchema/XMLSchema.xsd'
            ),
            Schema::withNamespace('http://unkown.com', 'https://unknown.com/schema.xsd')
        );
        $manipulator = overwrite_with_local_files([
            'http://www.w3.org/2001/XMLSchema' => '/local/XMLSchema.xsd'
        ]);

        $result = $schemas->manipulate($manipulator);

        static::assertNotSame($result, $schemas);
        static::assertEquals(
            [
                Schema::withoutNamespace('location.xsd'),
                Schema::withNamespace(
                    'http://www.w3.org/2001/XMLSchema',
                    '/local/XMLSchema.xsd'
                ),
                Schema::withNamespace('http://unkown.com', 'https://unknown.com/schema.xsd')
            ],
            [...$result]
        );
    }
}
