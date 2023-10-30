# Reader Component

The Reader component can be used to detect patterns in a memory-safe way inside a big XML document.
It uses matchers to determine if you care about specific elements or not.
As a result, the reader provides a generator of XML strings that match your matchers!

## Example

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Reader\Reader;
use VeeWee\Xml\Reader\Matcher;

$reader   = Reader::fromXmlFile('large-data.xml');
$provider = $reader->provide(
    Matcher\all(
        Matcher\node_name('item'),
        Matcher\node_attribute('locale', 'nl-BE')
    )
);

foreach ($provider as $nlItem) {
    $dom = Document::fromXmlString($nlItem);
    // Do something with it
}
```

## Reader

The Reader consists out of following composable blocks:

- [Configurators](#configurators): Configure how the Reader behaves.
- [Loaders](#loaders): Specify how the Reader loads the XML document.
- [Matchers](#matchers) Determine which XML elements you are interested in.

### Configurators

You can configure how a reader behaves based on configurators.
This package provides following configurators:

#### parser_options

You can specify one of [PHP's reader parser options](https://www.php.net/manual/en/class.xmlreader.php#xmlreader.constants.options):

```php
use VeeWee\Xml\Reader\Reader;
use XMLReader;
use function VeeWee\Xml\Reader\Configurator\parser_options;

$reader = Reader::fromXmlFile('some-file.xml', parser_options([
    XMLReader::LOADDTD => true,
    XMLReader::VALIDATE => true,
    XMLReader::DEFAULTATTRS => true,
    XMLReader::SUBST_ENTITIES => true,
]));
```

#### substitute_entities

Can be used to translate XML entities like &amp;amp; to their actual value.

```php
use VeeWee\Xml\Reader\Reader;
use function VeeWee\Xml\Reader\Configurator\substitute_entities;

$reader = Reader::fromXmlString($xml, substitute_entities());
```

#### xsd_schema

You can specify a XSD Schema that will be validated whilst reading:

```php
use VeeWee\Xml\Reader\Reader;
use function VeeWee\Xml\Reader\Configurator\xsd_schema;

$reader = Reader::fromXmlFile('some-file.xml', xsd_schema('schema.xsd'));
```

#### Writing your own configurator

A configurator can be any `callable` that is able to configure an `XMLReader`:

```php
namespace VeeWee\Xml\Reader\Configurator;

use XMLReader;

interface Configurator
{
    public function __invoke(XMLReader $reader): XMLReader;
}

```

You can use the new configurator instance as followed:

```php
use VeeWee\Xml\Reader\Reader;

$reader = Reader::configure($yourLoader, ...$configurators);
```

### Loaders

#### xml_file_loader

```php
use VeeWee\Xml\Reader\Reader;

$reader = Reader::fromXmlFile('some-file.xml', ...$configurators);
```

#### xml_string_loader

```php
use VeeWee\Xml\Reader\Reader;

$reader = Reader::fromXmlString('<xml />', ...$configurators);
```

#### Writing your own loader

A loader can be any `callable` that is able to create an `XMLReader`:

```php
namespace VeeWee\Xml\Reader\Loader;

use XMLReader;

interface Loader
{
    public function __invoke(): XMLReader;
}
```

You can create a new reader instance like this:

```php
use VeeWee\Xml\Reader\Reader;

$reader = Reader::configure($yourLoader, ...$configurators);
```

### Matchers

#### all

All provided matchers need to match in order for this matcher to succeed:

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\all(
    Matcher\node_name('item'),
    Matcher\node_attribute('locale', 'nl-BE')
);
```

ℹ️ If you specify no matchers internally, it will act as a "wildcard" matcher that always returns true.

#### any

One of the provided matchers need to match in order for this matcher to succeed:

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\any(
    Matcher\node_name('item'),
    Matcher\node_name('product'),
);
```

ℹ️ If you specify no matchers internally, it will act as a "never" matcher that always returns false.

#### attribute_local_name

Matches current element based on attribute exists: `locale`.
Also prefixed attributes will be matched `some:locale`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\attribute_local_name('locale');
```

#### attribute_local_value

Matches current element based on attribute value `locale="nl-BE"`.
Also prefixed attributes will be matched `some:locale="nl-BE"`. 

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\attribute_local_value('locale', 'nl-BE');
```

#### attribute_name

Matches current element based on attribute exists: `locale`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\attribute_name('locale');
// OR
Matcher\attribute_name('prefixed:locale');
```

#### attribute_value

Matches current element based on attribute value `locale="nl-BE"`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\attribute_value('locale', 'nl-BE');
// OR
Matcher\attribute_value('prefixed:locale', 'nl-BE');
```

#### document_element

Matches on the root document element only.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\document_element();
```

#### element_local_name

Matches current element on node name `<item />`.
Also prefixed elements will be matched: `<some:item />`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\element_local_name('item');
```

#### element_name

Matches current element on full node name `<item />`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\element_name('item');
// OR
Matcher\element_name('some:item');
```

#### element_position

Matches current element on the position of the element in the XML tree.
Given following example:

```xml
<items>
    <item />
    <item />
    <item />
</items>
```

Only the middle `<item />` will be matched.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\element_position(1);
```

#### namespaced_attribute

Matches current element based on attribute XMLNS namespace `https://some` and attribute key `locale`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\namespaced_attribute('https://some', 'locale');
```

#### namespaced_attribute_value

Matches current element based on attribute namespace `https://some` and value `locale="nl-BE"`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\namespaced_attribute_value('https://some', 'locale', 'nl-BE');
```

#### namespaced_element

Matches current element on namespace and element name `<item xmlns="https://some" />`.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\namespaced_element('https://some', 'item');
```

#### not

Inverses a matcher's result.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\not(
    Matcher\element_name('item')
);
```

#### sequence

Provide a sequence of matchers that represents the XML tree.
Only the items that are described by the sequence will match.

Given:

```xml
<root>
    <users>
        <user locale="nl">Jos</user>
        <user>Bos</user>
        <user>Mos</user>    
    </users>
</root>
```

This matcher will grab the `user` element with `locale="nl"`

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\sequence(
    // Level 0: <root />
    Matcher\document_element(),
    // Level 1: <users />
    Matcher\element_name('users'), 
    // Level 2: <user locale="nl">Jos</user>
    // Searches for all elements that matches `<user />` and attribute `locale="nl"`
    Matcher\all( 
        Matcher\element_name('user'),
        Matcher\attribute_value('locale', 'nl')
    )
);
```


#### Writing your own matcher

A matcher can be any `callable` that takes a `NodeSequence` as input and returns a `bool` that specifies if it matches or not:

```php
namespace VeeWee\Xml\Reader\Matcher;

use VeeWee\Xml\Reader\Node\NodeSequence;

interface Matcher
{
    public function __invoke(NodeSequence $sequence): bool;
}
```

The `NodeSequence` class can be seen as the breadcrumbs of the current XML.
It points to the current element and all of its attributes.
You can select the current element, its parent or a complete sequence of elements until the current one in order matches.
