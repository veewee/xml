<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsd\Manipulator;

use VeeWee\Xml\Xsd\Schema;
use VeeWee\Xml\Xsd\SchemaCollection;

/**
 * @param array<string, string> $map - Key=namspace, value=location
 *
 * @return callable(SchemaCollection): SchemaCollection
 */
function overwrite_with_local_files(array $map): callable
{
    return static fn (SchemaCollection $schemas): SchemaCollection =>
        new SchemaCollection(
            ...$schemas->map(
                static function (Schema $schema) use ($map): Schema {
                    if (!$namespace = $schema->namespace()) {
                        return $schema;
                    }

                    if (!array_key_exists($namespace, $map)) {
                        return $schema;
                    }

                    return Schema::withNamespace($namespace, $map[$namespace]);
                }
            )
        );
}
