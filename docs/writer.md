# Writer Component

The Writer component can be used to write a lot of XML in a memory-safe and readable way.
It uses builders to specify how the XML should be structured.

## Example

```php
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Builder\document;
use function VeeWee\Xml\Writer\Builder\attribute;
use function VeeWee\Xml\Writer\Builder\children;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Configurator\indentation;

$writer = Writer::forFile($someFile, indentation('  '));
$writer->write(
    document('1.0', 'UTF-8',
        element('hello', children([
            attribute('default', 'world'),
            element('item', value('Jos')),
            element('item', value('Bos')),
            element('item', value('Mos')),
        ]))
    )
);
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<hello default="world">
    <item>Jos</item>
    <item>Bos</item>
    <item>Mos</item>
</hello>
```


## Writer

The Writer consists out of following composable blocks:

- [Builders](#builders): Lets you build XML by using a declarative API.
- [Configurators](#configurators): Configure how the Writer behaves.
- [Mappers](#mappers): Map the XMLWriter to something else.
- [Openers](#openers): Specify where you want to write to.

## Builders

Lets you build XML by using a declarative API.

```php
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Builder\document;
use function VeeWee\Xml\Writer\Builder\children;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespace_attribute;
use function VeeWee\Xml\Writer\Builder\prefixed_attribute;
use function VeeWee\Xml\Writer\Configurator\indentation;

$writer = Writer::forFile($someFile, indentation('  '));
$writer->write(
    document('1.0', 'UTF-8',
        element('hello',
            namespace_attribute('http://helloworld.com', 'hello'),
            children([
                element('item',
                    prefixed_attribute('hello', 'default', 'true'),
                    value('Jos')
                ),
                element('item', value('Bos')),
                element('item', value('Mos')),
            ]),
            children($provideYourOwnElementGenerators)
        )
    )
);
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<hello xmlns:hello="http://helloworld.com">
    <item hello:default="true">Jos</item>
    <item>Bos</item>
    <item>Mos</item>
    <!-- The tags provided by $provideYourOwnElementGenerators -->
</hello>
```

#### attribute

Writes a single attribute to an element:

```php
use function VeeWee\Xml\Writer\Builder\attribute;
use function VeeWee\Xml\Writer\Builder\element;

element('foo',
    attribute('bar', 'baz')
);
```

```xml
<foo bar="baz" />
```

#### attributes

Write multiple attributes to an element:

```php
use function VeeWee\Xml\Writer\Builder\attributes;
use function VeeWee\Xml\Writer\Builder\element;

element('foo',
    attributes([
        'hello' => 'world',
        'bar' => 'baz',
    ])
);
```

```xml
<foo hello="world" bar="baz" />
```

#### cdata

Writes a CDATA section:

```php
use function VeeWee\Xml\Dom\Builder\value;
use function VeeWee\Xml\Writer\Builder\cdata;
use function VeeWee\Xml\Writer\Builder\element;

element('foo',
    cdata(value('some cdata'))
);
```

```xml
<foo><![CDATA[some cdata]]></foo>
```

#### children

Inserts multiple nodes at current position in the writer.

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\children;

element('hello',
    children([
        element('world'),
        element('you')
    ])
);
```

```xml
<hello>
    <world />
    <you />
</hello>
```

It takes any `iterable` as input, meaning that you could use this function as an entry-point to couple your own memory-safe data into the builder.
For example:

```php
use function MyCustom\Generator\db_data;
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\children;

function myOwnDataBuilder(): Generator
{
    foreach (db_data() as $record) {
        yield element('item', value($record['name']));
    } 
}

children(myOwnDataBuilder());
```

#### comment

Writes a comment section:

```php
use function VeeWee\Xml\Dom\Builder\value;
use function VeeWee\Xml\Writer\Builder\comment;
use function VeeWee\Xml\Writer\Builder\element;

element('foo',
    comment(value('some comment'))
);
```

```xml
<foo><!--some comment--></foo>
```

#### document

This builder can be used to specify an XML version and charset.
However, it is not required to wrap a document. You could as well start out with a root element instead!

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\document;

document('1.0', 'UTF-8',
    element('root')
);
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root />
```

#### element

Builds a regular XML element.
It can contain a set of configurators that can be used to specify the attributes, children, value, ... of the element.

```php
use function VeeWee\Xml\Writer\Builder\element;

element('hello', ...$configurators);
```

```xml
<hello />
```

#### namespace_attribute

Can be used to add an `xlmns` attribute to an element.
You can choose to only add the `xmlns` attributes to a parent attribute.
This way you don't end up with having the namespace URI all over the place.

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespace_attribute;

element('foo',
    namespace_attribute('https://acme.com'),
    namespace_attribute('https://helloworld.com', 'hello')
);
```

```xml
<foo xmlns="https://acme.com" xmlns:hello="https://helloworld.com" />
```


#### namespaced_attribute

Can be used to add a namespaced attribute with or without prefix.
This function will also add the xmlns attribute.

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespaced_attribute;

element('foo',
    namespaced_attribute('https://acme.com', 'acme', 'hello', world)
);
```

```xml
<foo xmlns:acme="https://acme.com" acme:hello="world" />
```

#### namespaced_attributes

Can be used to add multiple namespaced attributes with or without prefix at once.
This function will add the xmlns attribute.

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespaced_attributes;

element('foo',
    namespaced_attributes('https://acme.com', [
        'acme:hello' => 'world',
        'acme:foo' => 'bar',
    ])
);
```

```xml
<foo xmlns:acme="https://acme.com" acme:hello="world" acme:foo="bar" />
```

#### namespaced_element

Build a namespaced element.
It can contain a set of configurators that can be used to specify the attributes, children, value, ... of the element with or without prefix.
This function will add the xmlns attribute.

```php
use function VeeWee\Xml\Writer\Builder\namespace_attribute;
use function VeeWee\Xml\Writer\Builder\prefixed_element;

namespaced_element('http://acme.com', 'acme', 'hello',
    ...$configurators
);
```

```xml
<acme:hello xmlns:acme="http://acme.com" />
```

#### prefixed_attribute

Can be used to add a namespaced prefixed attribute.
This function won't add the xmlns attribute. You need to manually specify it with the `namespace_attribute` function.

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespace_attribute;
use function VeeWee\Xml\Writer\Builder\prefixed_attribute;

element('foo',
    namespace_attribute('https://acme.com', 'acme'),
    prefixed_attribute('acme', 'hello', 'world'),
);
```

```xml
<foo xmlns:acme="https://acme.com" acme:hello="world" />
```

#### prefixed_attributes

Can be used to add multiple prefixed attributes at once.
This function won't add the xmlns attribute. You need to manually specify it with the `namespace_attribute` function.

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\namespace_attribute;
use function VeeWee\Xml\Writer\Builder\prefixed_attributes;

element('foo',
    namespace_attribute('https://acme.com', 'acme'),
    prefixed_attributes([
        'acme:hello' => 'world',
        'acme:foo' => 'bar',
    ])
);
```

```xml
<foo xmlns:acme="https://acme.com" acme:hello="world" acme:foo="bar" />
```

#### prefixed_element

Build a namespace prefixed element.
It can contain a set of configurators that can be used to specify the attributes, children, value, ... of the element.
This function won't add the xmlns attribute. You need to manually specify it with the `namespace_attribute` function.

```php
use function VeeWee\Xml\Writer\Builder\namespace_attribute;
use function VeeWee\Xml\Writer\Builder\prefixed_element;

prefixed_element('acme', 'hello',
    namespace_attribute('http://acme.com', 'acme'),
    ...$configurators
);
```

```xml
<acme:hello xmlns:acme="http://acme.com" />
```

#### raw

Can be used to insert raw strings into the XML.
Be careful: these strings won't be validated or escaped and are just appended to the XML without any sort of validation. 

```php
use function VeeWee\Xml\Writer\Builder\raw;

raw('<hello>world</hello>');
```

```xml
<hello>world</hello>
```

#### value

Can set a value to any XML element.

```php
use function VeeWee\Xml\Writer\Builder\element;
use function VeeWee\Xml\Writer\Builder\value;

element('hello', value('world'));
```

```xml
<hello>world</hello>
```

#### Writing your own builder

A builder can be any `callable` that takes a `XMLWriter` and yields write actions on it:

```php
namespace VeeWee\Xml\Writer\Builder;

use Generator;
use XMLWriter;

interface Builder
{
    /**
     * @returns \Generator<bool> 
     */
    public function __invoke(XMLWriter $writer): Generator;
}
```

For example:

```php
use VeeWee\Xml\Writer\Builder\Builder;

class MyAttribute implements Builder
{
    public function __invoke(XMLWriter $writer) : Generator
    {
        yield $writer->startAttribute('my-attrib');
        yield $writer->text('my-value');
        yield $writer->endAttribute();
    }
}
```

## Configurators

Specify how you want to configure the XML writer.

#### open

The opener configurator takes an [opener](#openers) to specify the target of the writer.

```php
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Configurator\open;
use function VeeWee\Xml\Writer\Opener\xml_file_opener;

Writer::configure(
    open(xml_file_opener('somefile.xml'))
);
```

#### indentation

By default, the writer does not indent.
With this configurator, you can specify how you want to indent the XML file:

```php
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Configurator\indentation;

Writer::forFile(
    'some.xml',
    indentation('   ')
);
```

Now, every XML level will be indented with the specified indentation string!

#### Writing your own configurator

A configurator can be any `callable` that takes a `XMLWriter` and configures it:

```php
namespace VeeWee\Xml\Writer\Configurator;

use XMLWriter;

interface Configurator
{
    public function __invoke(XMLWriter $writer): XMLWriter;
}
```

You can apply the configurator as followed:

```php
use VeeWee\Xml\Writer\Writer;

$document = Writer::configure(...$configurators);
```

## Mapper

#### memory_output

If you are using an in-memory writer, you can use the `memory_output()` mapper to get the written content as a string.

```php
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Mapper\memory_output;

$doc = Writer::inMemory()
    ->write($yourXml)
    ->map(memory_output());
```

## Openers

#### memory_opener

Starts a writer that stores the written XML in-memory.
You can use the [memory_output](#memoryoutput) mapper to retrieve the written XML.

```php
use VeeWee\Xml\Writer\Writer;
use function VeeWee\Xml\Writer\Mapper\memory_output;

$doc = Writer::inMemory(...$configurators)
    ->write($yourXml)
    ->map(memory_output());
```

#### xml_file_opener

Loads an XML document from a file.
When the file or folder does not exists, the code will attempt to create it.
If it is not possible to create a target to write to, a `RuntimException` will be thrown.

```php
use VeeWee\Xml\Writer\Writer;

$doc = Writer::forFile('some-xml.xml', ...$configurators);
```

#### Writing your own opener

```php
namespace VeeWee\Xml\Writer\Opener;

use XMLWriter;

interface Opener
{
    public function __invoke(XMLWriter $writer): bool;
}
```

You can apply the loader as followed:

```php
namespace VeeWee\Xml\Writer\Writer;

$writer = Writer::configure($loader, ...$configurators);
```
