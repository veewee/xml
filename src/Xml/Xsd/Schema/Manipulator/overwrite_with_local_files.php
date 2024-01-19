<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsd\Schema\Manipulator;

use Closure;
use VeeWee\Xml\Xsd\Schema\Schema;
use VeeWee\Xml\Xsd\Schema\SchemaCollection;

/**
 * @param array<string, string> $map - Key=namspace, value=location
 *
 * @return Closure(SchemaCollection): SchemaCollection
 */
function overwrite_with_local_files(array $map): Closure
{
    return static fn (SchemaCollection $schemas): SchemaCollection =>
        new SchemaCollection(
            ...$schemas->map(
                static function (Schema $schema) use ($map): Schema {
                    /** @psalm-suppress RiskyTruthyFalsyComparison */
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
