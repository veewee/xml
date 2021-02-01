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
use function VeeWee\Xml\Dom\Manipulator\append;

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

The loader configurator takes a [loader](#loaders) to specify the source of the DOM Document.

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

Locators can be used to search for specific elements inside your DOM document.
The locators are split up based on what they are locating.

### Document

The Document locators can be called directly from the `Document` class.

#### elements_with_namespaced_tagname

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\elements_with_namespaced_tagname;

$doc = Document::fromXmlFile('some.xml');
$products = $doc->locate(elements_with_namespaced_tagname('http://amazon.com', 'product'));
```

#### elements_with_tagname

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\elements_with_tagname;

$doc = Document::fromXmlFile('some.xml');
$products = $doc->locate(elements_with_tagname('product'));
```

### Element

These locators can be run on `DOMElement` instances.

#### locate_by_namespaced_tag_name

```php
use function VeeWee\Xml\Dom\Locator\Element\locate_by_namespaced_tag_name;

$products = locate_by_namespaced_tag_name($element, 'http://amazon.com', 'product');
```

#### locate_by_tag_name

```php
use function VeeWee\Xml\Dom\Locator\Element\locate_by_tag_name;

$products = locate_by_tag_name($element, 'product');
```

### Node

These locators can be run on any `DOMNode` instance.

#### Node\children

Fetch all child elements from a specific `DOMNode`.

```php
use function VeeWee\Xml\Dom\Locator\Node\children;

$childElements = children($element);
```

#### Node\detect_document

Fetch the `DOMDocument` to which a node is linked.
If the node is not linked to a document yet, it throws a `InvalidArgumentException`.

```php
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

$document = detect_document($element);
```

#### Node\value

Fetch the value from the provided `DOMNode` and coorce it to a specific type.

```php
use Psl\Type;
use function VeeWee\Xml\Dom\Locator\Node\value;

$productPrice = value($product, Type\float());
```

### Xsd

Locates internally applied XSD schema's from a specific `DOMDocument`.

#### Xsd\locate_all_xsd_schemas

Locate both namespaced as no namespaced XSD schema's that are added to the XML document.

```php
use function VeeWee\Xml\Dom\Locator\Xsd\locate_all_xsd_schemas;

/** @var Generator<string> $schemas */
$schemas = locate_all_xsd_schemas($document);
```

#### Xsd\locate_namespaced_xsd_schemas

Locate only the namespaced XSD schema's that are added to the XML document.

```php
use function VeeWee\Xml\Dom\Locator\Xsd\locate_namespaced_xsd_schemas;

/** @var Generator<string> $schemas */
$schemas = locate_namespaced_xsd_schemas($document);
```

#### Xsd\locate_no_namespaced_xsd_schemas

Locate only the no namespaced XSD schema's that are added to the XML document.

```php
use function VeeWee\Xml\Dom\Locator\Xsd\locate_no_namespaced_xsd_schemas;

/** @var Generator<string> $schemas */
$schemas = locate_no_namespaced_xsd_schemas($document);
```

## Manipulators

Allows you to manipulate any DOM document.

### Document

Document specific manipulators can directly be applied to `Document` objects.

#### append

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Manipulator\append;

$doc = Document::empty();
$doc->manipulate(
    append(...$doc->build(
        element('root')
    ))
);
```

### Node

Node specific manipulators operate on `DOMNode` instances.

#### append_external_node

Makes it possible to append a `DOMNode` from an external document into a `DOMNode` from the current document.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

$copiedNode = append_external_node($documentNode, $externalNode);
```

#### import_node_deeply

Makes it possible to import a full `DOMNode` from an external document so that it can be used in current document.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\import_node_deeply;

$copiedNode = import_node_deeply($documentNode, $externalNode);
```

#### replace_by_external_node

Makes it possible to replace a `DOMNode` from current document with a `DOMNode` from an external document.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_node;

$copiedNode = replace_by_external_node($documentNode, $externalNode);
```

## Mappers

Converts the DOM document to something else.

#### xml_string

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlFile('some.xml');
$xml = $doc->toXmlString();
```

Instead of mapping a full document, you can also map a specific node only to XML.

```php
use function VeeWee\Xml\Dom\Mapper\xml_string;

$mapper = xml_string();
$xml = $mapper($someNode);
```

#### xslt_template

Allows you to map an XML document based on an [XSLT template](xslt.md).

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\xslt_template;

$doc = Document::fromXmlFile('data.xml');
$xsl = Document::fromXmlFile('xml-to-yaml-converter.xslt');

echo $doc->map(xslt_template($xsl));
```

#### Writing your own mapper


A configurator can be any `callable` that takes a `DOMDocument` and configures it:

```php
namespace VeeWee\Xml\Dom\Mapper;

use DOMDocument;

/**
 * @template R
 */
interface Mapper
{
    /**
     * @return R
     */
    public function __invoke(DOMDocument $document): mixed;
}

```

You can apply the mapper as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::fromXmlFile('some.xml');
$result = $document->map($mapper);
```

## Validators

Validate the content of your XML document.

#### internal_xsd_validator

Validates the document based on all internally specified XML schema's.

```php
use VeeWee\XML\DOM\Document;
use function VeeWee\Xml\Dom\Validator\internal_xsd_validator;

$doc = Document::fromXmlFile('some.xml');
$issues = $doc->validate(internal_xsd_validator());
```

#### validator_chain

Can be used to validate with multiple validators. The result is a combined list of issues!

```php
use VeeWee\XML\DOM\Document;
use function VeeWee\Xml\Dom\Validator\validator_chain;
use function VeeWee\Xml\Dom\Validator\internal_xsd_validator;
use function VeeWee\Xml\Dom\Validator\xsd_validator;

$doc = Document::fromXmlFile('some.xml');
$issues = $doc->validate(validator_chain(
    internal_xsd_validator(),
    xsd_validator('myown.xsd')
));
```

#### xsd_validator

Makes it possible to validate an XML against a specific XSD file. 

```php
use VeeWee\XML\DOM\Document;
use function VeeWee\Xml\Dom\Validator\xsd_validator;

$doc = Document::fromXmlFile('some.xml');
$issues = $doc->validate(xsd_validator('myown.xsd'));
```

# XPath

One of the most commonly used components inside DOM is the XPath component.
Since it takes a lot of effort to configure XPath, we provided our own component that improves both configuration and error handling.

Following components are available:

* [Configurators](#xpath-configurators): Can be used to configure an XPath object.
* [Locators](#xpath-locators): Can be used to locate specific queries.

## Xpath\Configurators

Can be used to configure an XPath object.

#### all_functions

Registers all known PHP functions to the XPath object, allowing you to use `php:somefunction()` inside your XPath query.

```php
use VeeWee\XML\DOM\Document;
use function VeeWee\XML\DOM\Xpath\Configurator\all_functions;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(all_functions());
```

#### functions

Registers a list of known PHP functions to the XPath object, allowing you to use `php:somefunction()` inside your XPath query.

```php
use VeeWee\XML\DOM\Document;
use function VeeWee\XML\DOM\Xpath\Configurator\functions;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(functions(['has_multiple']));
```

#### namespaces

Registers a map of namespaces with their prefix to the XPath object. 
This allows you to use a prefix name for a specific namespace. E.g. `//soap:envelope`.

```php
use VeeWee\XML\DOM\Document;
use function VeeWee\XML\DOM\Xpath\Configurator\namespaces;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(
    namespaces([
        'soap' => 'http://schemas.xmlsoap.org/wsdl/',
        'xsd' => 'http://www.w3.org/1999/XMLSchema',
    ])
);
```

#### php_namespace

Registers the `php` namespace in order to allow registration of php functions.

```php
use VeeWee\XML\DOM\Document;
use function VeeWee\XML\DOM\Xpath\Configurator\php_namespace;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(php_namespace());
```

#### Writing your own XPath configurator

A configurator can be any `callable` that takes a `DOMXPath` and configures it:

```php
namespace VeeWee\Xml\Dom\Xpath\Configurator;

use DOMXPath;

interface Configurator
{
    public function __invoke(DOMXPath $xpath): DOMXPath;
}
```

You can apply the XPath configurator as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::configure('some.xml');
$document->xpath(...$configurators);
```

## Xpath\Locators

Can be used to locate specific queries.

#### evaluate

Evaluates an XPath query in a type-safe way:

```php
use Psl\Type;
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath();
$count = $xpath->evaluate('count(.//item)', Type\int());
```

You can also pass a context-node in which the evaluation is done:

```php
$count = $xpath->evaluate('count(.//item)', Type\int(), $productsElement);
```

#### query

Run a specific XPath query and expect to get back a list of matching `DOMElement`.

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath();
$productList = $xpath->query('/products');
```

You can also pass a context-node in which the query is performed:

```php
$productList = $xpath->query('/products', $rootNode);
```

#### query_single

In many situations, you only expect one specific element to be available.
Instead of querying for a list, you can also query for a single element.
It expects to find exactly one element and throws an `InvalidArgumentException` if that is not the case.

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath();
$productName = $xpath->querySingle('/products/name');
```

You can also pass a context-node in which the query is performed:

```php
$productName = $xpath->querySingle('/name', $product);
```

#### Writing your own XPath locator

A XPath locator can be any `callable` that takes a `DOMXPath` and locates something on it:


```php
namespace VeeWee\Xml\Dom\Xpath\Locator;

use DOMXPath;

/**
 * @template T
 */
interface Locator
{
    /**
     * @return T
     */
    public function __invoke(DOMXPath $xpath): mixed;
}
```

You can apply the locator as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::configure('some.xml');
$xpath = $document->xpath();

$result = $xpath->locate($locator);
```
