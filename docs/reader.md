# Reader Component

The Reader component can be used to detect patterns in a memory-safe way inside a big XML document.
It uses matchers to determine if you care about specific elements or not.
As a result, the reader provides a generator of XML strings that match your matchers!

## Example

```php
use VeeWee\Xml\Dom\Configurator;
use VeeWee\Xml\Reader\Reader;
use VeeWee\Xml\Reader\Signal;
use VeeWee\Xml\Reader\Matcher;

$reader   = Reader::fromXmlFile('large-data.xml');
$provider = $reader->provide(
    $matcher = Matcher\all(
        Matcher\element_name('item'),
        Matcher\attribute_value('locale', 'nl-BE')
    ),
    // Optionally, you can provide a signal to stop reading at a given point:
    $signal = new Signal()
);

foreach ($provider as $nlItem) {
    // Do something with it
    $xml = $nlItem->xml();
    $dom = $nlItem->intoDocument(Configurator\canonicalize());
    $decoded = $nlItem->decode(Configurator\canonicalize());
    $matched = $nlItem->matches($matcher);
    $sequence = $nlItem->nodeSequence();
    
    // If you have loaded sufficient items, you can stop reading the XML file:
    $signal->stop();
}
```

## How does it work?

Given following example:

```xml
<breakfast_menu>
  <food soldOut="false" bestSeller="true">
    <name>Belgian Waffles</name>
    <price>$5.95</price>
    <description>Two of our famous Belgian Waffles with plenty of real maple syrup</description>
    <calories>650</calories>
  </food>
  <food soldOut="false" bestSeller="false">
    <name>Strawberry Belgian Waffles</name>
    <price>$7.95</price>
    <description>Light Belgian waffles covered with strawberries and whipped cream</description>
    <calories>900</calories>
  </food>
</breakfast_menu>
```

The reader will stream the content of the XML through PHP's XMLReader which works stream-based internally.
It keeps track of the elements it visits by storing all parent elements inside a `NodeSequence`.
You can think about this NodeSequence as breadcrumbs: In current XML, it could look like `breakfast_menu > food > name`.

The reader will keep only small parts of the XML in memory by reading the XML stream in chunks.
When the reader detects the first `breakfast_menu` element, it will ask the provided matchers if you are interested in this tag.
A matcher is a function that returns `true` when interested or `false` when it is not interested in this element.
When the matcher returns `true`, the reader will read the complete outer XML of current tag and `yield` this matching XML to your logic.
This XML is wrapped in a `MatchingNode` which also contains the `NodeSequence` and some handy shortcut functions to e.g. convert the XML into a DOM Document.
Do note that, the memory-safety of YOUR reader is based on the part inside the XML you are interested in:
If you only match on the root node, it will yield the complete XML and therefore won't be memory-safe.

After deciding if you are interested in the previous tag, it jumps over to the next tag: `breakfast_menu > food[position() = 1 AND @soldOUt=false AND @bestSeller = true]` and asks the matcher if you are interested in this.
As you can see, also the attributes and position of the element inside its parent will be available inside the `NodeSequence`.
This makes it possible to perform very exact / filtered matches.
Then it goes even deeper in its children and asks if you are interested in:

* `breakfast_menu > food[position() = 1 AND @soldOUt=false AND @bestSeller = true] > name`
* `breakfast_menu > food[position() = 1 AND @soldOUt=false AND @bestSeller = true] > price`
* `breakfast_menu > food[position() = 1 AND @soldOUt=false AND @bestSeller = true] > description`
* `breakfast_menu > food[position() = 1 AND @soldOUt=false AND @bestSeller = true] > calories`

Before it jumps back into the next `food` element to see if you are interested in:

* `breakfast_menu > food[position() = 2 AND @soldOUt=false AND @bestSeller = false]`
* `breakfast_menu > food[position() = 2 AND @soldOUt=false AND @bestSeller = false] > name`
* `breakfast_menu > food[position() = 2 AND @soldOUt=false AND @bestSeller = false] > price`
* `breakfast_menu > food[position() = 2 AND @soldOUt=false AND @bestSeller = false] > description`
* `breakfast_menu > food[position() = 2 AND @soldOUt=false AND @bestSeller = false] > calories`

When the reader gets to the bottom of the XML, the reading is finished and all the matches you are interested in are yielded.

Matchers act similar to the [specification pattern](https://en.wikipedia.org/wiki/Specification_pattern).
The cool part about this, is that it allows you to combine multiple matchers exactly as you would do with booleans.
For example: `sequence(element_name('breakfast_menu', element_name('food'))` will look for the NodeSequence that matches `breakfast_menu > food`.


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
    Matcher\element_name('item'),
    Matcher\attribute_value('locale', 'nl-BE')
);
```

ℹ️ If you specify no matchers internally, it will act as a "wildcard" matcher that always returns true.

#### any

One of the provided matchers need to match in order for this matcher to succeed:

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\any(
    Matcher\element_name('item'),
    Matcher\element_name('product'),
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

#### nested

Provide nested matchers that represents parts of an XML tree.
It can be used similar to the `//user` xpath operator to search on any matching node at any level in the XML 

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

Matcher\nested(
    // Breakpoint 1: <root />
    Matcher\document_element(),
    // Breakpoint 2: <user locale="nl">Jos</user>
    // Searches for all elements that matches `<user />` and attribute `locale="nl"` in the `<root />` document.
    // Note that you can skip matching on `<users />` here : it's not an exact matcher
    Matcher\all( 
        Matcher\element_name('user'),
        Matcher\attribute_value('locale', 'nl')
    )
);
```

Every provided matcher acts as a breakpoint in the `NodeSequence` for the next matcher,
making it composable with the exact XML tree [sequence](#sequence) matcher as well.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\nested(
    // Breakpoint 1: <root />
    Matcher\document_element(),
    // Breakpoint 2: <user />
    // The nested matcher will provide the NodeSequence starting from the element after previous match.
    // The sequence will basically receive: 'users > user'
    Matcher\sequence( 
        // Level 0: The element inside <root /> at level 0 must exactly match <users /> 
        Matcher\element_name('users'),
        // Level 1: The element inside <root /> at level 1 must exactly match <user />
        Matcher\element_name('user'),
    ),
    // Breakpoint 3: <email />
    // After matching a sequence, you can still continue matching deeper or adding even more sequences:
    Matcher\element_name('email')
);
```

If you want every level of the XML to match exactly, you might use the [sequence](#sequence) matcher instead.

#### not

Inverses a matcher's result.

```php
use \VeeWee\Xml\Reader\Matcher;

Matcher\not(
    Matcher\element_name('item')
);
```

#### sequence

Provide a sequence of matchers that represents the exact XML tree.
Every provided matcher step must result in an exact match with the matcher on the same index.
It can be used similar to the `/root/users/user` xpath operator to search on an exact node match at every level in the XML.
Only the items that are described by the sequence will match:

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

If you don't want every level of XML to match exactly, you might use the [nested](#nested) matcher instead.


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
