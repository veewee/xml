# XSD Components

Tools for working with XSD schemas.

## Example

```php
use VeeWee\XML\DOM\Document;
use VeeWee\Xml\Xsd\Schema\Manipulator;
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;

$doc = Document::fromXmlFile('some.xml');
$schemas = locate_all_xsd_schemas($doc->toUnsafeDocument())
    ->manipulate(Manipulator\base_path('/var/www'))
    ->manipulate(Manipulator\overwrite_with_local_files([
        'http://www.w3.org/2001/XMLSchema' => '/local/XMLSchema.xsd'
    ]));
```

It consists out of following components:

* [Schemas](#schemas): Can be used to create a list of one or multiple XSD schemas.


## Schemas

Can be used to keep track of the detected schema's inside a XML document.

This is what a schema looks like:

```php
namespace VeeWee\Xml\Xsd\Schema;

#[Immutable]
final class Schema
{
    public static function withoutNamespace(string $location): self;
    public static function withNamespace(string $namespace, string $location): self;

    public function namespace(): ?string;
    public function location(): string;

    public function withLocation(string $location): self;
}
```

In XML, you'll often have a collection of schema's to work with:

```php
namespace VeeWee\Xml\Xsd\Schema;

#[Extends iterable<Schema>]
#[Immutable]
final class SchemaCollection implements Countable, IteratorAggregate
{
    // Check the source code for a list of all functions you can use on this collection! 
}
```

This package provides some tools to work with schema's:

* [Manipulators](#schema-manipulators): Can be used to manipulate a list of detected XSD schemas.

### Schema Manipulators


## Manipulators

Can be used to manipulate a list of detected XSD schemas.

#### base_path

If your XML document contains relative paths, you need to determine a base path.
This base path can be a path on the server or a URL.

```php
use VeeWee\Xml\Xsd\Schema\Manipulator;

$schemas = $schemas->manipulate(
    Manipulator\base_path('/var/www')
);
```

#### overwrite_with_local_files

Some files on e.g. W3.org can take forever to download over the internet.
They put a sleep in place so that packages like this won't spam them every second.
You can however, locally download the files and map their namespace to the local file like this the snippet below.

That way, you won't have to wait forever in order to validate your XML file!


```php
use VeeWee\Xml\Xsd\Schema\Manipulator;

$schemas = $schemas->manipulate(Manipulator\overwrite_with_local_files([
    'http://www.w3.org/2001/XMLSchema' => '/local/XMLSchema.xsd'
]));
```

### Writing your own manipulator

A manipulator can be any `callable` that takes a `SchemaCollection` and manipulates it into a new `SchemaCollection`.

```php
namespace VeeWee\Xml\Xsd\Schema\Manipulator;

use VeeWee\Xml\Xsd\Schema\SchemaCollection;

interface Manipulator
{
    public function __invoke(SchemaCollection $schemaCollection): SchemaCollection;
}
```

