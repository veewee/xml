<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsd\Manipulator;

use VeeWee\Xml\Xsd\Schema;
use VeeWee\Xml\Xsd\SchemaCollection;
use function strlen;
use const PHP_URL_SCHEME;

/**
 *
 * @return callable(SchemaCollection): SchemaCollection
 */
function base_path(string $basePath): callable
{
    /**
     * Thanks Symfony!
     * TODO : once we require this logic somehwere else, either import it or create a separate function!
     *
     * @see https://github.com/symfony/filesystem/blob/4ff1d2e04790e021941a9bcbed5aca383f8250da/Filesystem.php#L565-L579
     */
    $isAbsolutePath = static fn (string $path): bool
        => '' !== $path && (
            strspn($path, '/\\', 0, 1)
            || (
                strlen($path) > 3 && ctype_alpha($path[0])
                && ':' === $path[1]
                && strspn($path, '/\\', 2, 1)
            )
            || null !== parse_url($path, PHP_URL_SCHEME)
        );

    return static fn (SchemaCollection $schemas): SchemaCollection =>
        new SchemaCollection(
            ...$schemas->map(
                static fn (Schema $schema): Schema
                    => $isAbsolutePath($schema->location()) ? $schema : $schema->withLocation(
                        rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR . $schema->location()
                    )
            )
        );
}
