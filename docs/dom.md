# DOM Component

The DOM Components operates on XML documents through the DOM API.
Instead of solely wrapping a DOMDocument with our own class,
we embrace the fact that the DOM implementation is leaky.
This package provides a set of composable tools that allow you to safely work with the DOM extension.

Since not all code is in one big master class, you will find out that it is not too hard to write your own extensions!

## Examples

```php
use Psl\Type;
use VeeWee\XML\DOM\Configurator;
use VeeWee\XML\DOM\Document;
use VeeWee\XML\DOM\Validator;
use VeeWee\XML\DOM\Xpath;

$doc = Document::fromXmlFile(
    'data.xml',
    Configurator\utf8(),
    Configurator\trim_spaces(),
    Configurator\validator(
        Validator\internal_xsd_validator()
    ),
    new MyCustomMergeImportsConfigurator()
);

$xpath = $doc->xpath(
    Xpath\Configurator\namespaces([
        'soap' => 'http://schemas.xmlsoap.org/wsdl/',
        'xsd' => 'http://www.w3.org/1999/XMLSchema',
    ])
);

$currentNode = $xpath->querySingle('//products');
$count = $xpath->evaluate('count(.//item)', Type\int(), $currentNode);
```

Of course, the example above only gives you a small idea of all the implemented features.
Let's find out more by segregating the DOM component into its composable blocks:

* [Builders](#builders): Let you build XML by using a declarative API.
* [Configurators](#configurators): Specify how you want to configure your DOM document.
* [Loaders](#loaders): Determine where the XML should be loaded from.
* [Locators](#locators): Enables you to locate specific XML elements.
* [Manipulators](#manipulators): Allows you to manipulate any DOM document.
* [Mappers](#mappers): Converts the DOM document to something else.
* [Validators](#validators): Validate the content of your XML document.
* [XPath](#xpath): Query for specific elements based on XPath queries.


## Builders

Let you build XML by using a declarative API.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\children;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_element;
use function VeeWee\Xml\Dom\Builder\value;
use function VeeWee\Xml\Dom\Manipulator\Node\append;


$doc = Document::empty();
$doc->manipulate(
    append(...$doc->build(
        element('root', children(
            element('foo',
                attribute('bar', 'baz'),
                value('hello')
            ),
            namespaced_element('http://namespace', 'foo',
                attribute('bar', 'baz'),
                children(
                    element('hello', value('world'))
                )
            )
        ))
    ))
);
```

```xml
<root>
    <foo bar="baz">hello</foo>
    <foo bar="baz" xmlns="http://namespace">
        <hello>world</hello>
    </foo>
</root>
```

#### attribute

Operates on a `DOMElement` and adds the attribute with specified key and value

```php
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\element;

element('foo',
    attribute('bar', 'baz')
);
```

```xml
<foo bar="baz" />
```

#### attributes

Operates on a `DOMElement` and adds multiple attributes with specified key and value

```php
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\element;

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

#### children

Operates on a `DOMNode` and attaches multiple child nodes.

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\children;

element('hello',
    children(
        element('world'),
        element('you')
    )
);
```

```xml
<hello>
    <world />
    <you />
</hello>
```

#### element

Operates on a `DOMNode` and creates a new element.
It can contain a set of configurators that can be used to specify the attributes, children, value, ... of the element.

```php
use function VeeWee\Xml\Dom\Builder\element;

element('hello', ...$configurators);
```

```xml
<hello />
```

#### namespaced_attribute

Operates on a `DOMElement` and adds a namespaced attribute with specified key and value

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_attribute;

element('foo',
    namespaced_attribute('https://acme.com', 'acme:hello', 'world')
);
```

```xml
<foo xmlns:acme="https://acme.com" acme:hello="world" />
```

#### namespaced_attributed


Operates on a `DOMElement` and adds a namespaced attribute with specified key and value

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_attributes;

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

Operates on a `DOMNode` and creates a new namespaced element.
It can contain a set of configurators that can be used to specify the attributes, children, value, ... of the element.

```php
use function VeeWee\Xml\Dom\Builder\namespaced_element;

namespaced_element('http://acme.com', 'hello', ...$configurators);
```

```xml
<hello xmlns="http://acme.com" />
```

#### value

Operates on a `DOMElement` and sets the node value.

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\value;

element('hello', value('world'));
```

```xml
<hello>world</hello>
```

## Configurators

Specify how you want to configure your DOM document.

#### loader

The loader configurator takes a [loader](#loader) to specify the source of the DOM Document.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\loader;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

Document::configure(
    loader(xml_string_loader('<xml />'))
);
```

#### trim_spaces

Trims all whitespaces from the DOM document in order to make it as small as possible in bytesize.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\trim_spaces;

$doc = Document::fromXmlFile(
    'data.xml',
    trim_spaces()
);
```

#### utf8

Marks the DOM document as UTF-8.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\utf8;

$doc = Document::fromXmlFile(
    'data.xml',
    utf8()
);
```

#### validator

Takes a [Validator](#validators) as argument and validates the DOM.
Additionally, you can specify a maximum error level.
If this level is reached, an exception is thrown.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\ErrorHandling\Issue\Level;
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\Dom\Validator\internal_xsd_validator;

$doc = Document::fromXmlFile(
    'data.xml',
    validator(internal_xsd_validator(), Level::warning())
);
```

#### Writing your own configurator

A configurator can be any `callable` that takes a `DOMDocument` and configures it:

```php
namespace VeeWee\Xml\DOM\Configurator;

use DOMDocument;

interface Configurator
{
    public function __invoke(DOMDocument $document): DOMDocument;
}
```

You can apply the configurator as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::configure($loader, ...$configurators);
```

## Loaders

#### xml_file_loader

Loads an XML document from a file.

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlFile('some-xml.xml', ...$configurators);
```

#### xml_node_loader

Loads an XML document from an external `DOMNode`.

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlNode($someExternalNode, ...$configurators);
```

#### xml_string_loader

Loads an XML document from a string.

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlString('<xml />', ...$configurators);
```

#### Writing your own loader

```php
namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;
use Psl\Result\ResultInterface;

interface Loader
{
    /**
     * @return ResultInterface<true>
     */
    public function __invoke(DOMDocument $document): ResultInterface;
}
```

You can apply the loader as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::configure($loader, ...$configurators);
```

## Locators
## Manipulators
## Mappers
## Validators
## XPath
