# DOM Component

The DOM Components operate on XML documents through the DOM API.
Instead of solely wrapping a XMLDocument with our own class,
we embrace the fact that the DOM implementation is leaky.
This package provides a set of composable tools that allow you to safely work with the DOM extension.

Since not all code is in one big master class, you will find that it is not too hard to write your own extensions!

## Examples

```php
use \Dom\XMLDocument;
use Psl\Type;
use VeeWee\Xml\Dom\Configurator;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Loader;
use VeeWee\Xml\Dom\Validator;
use VeeWee\Xml\Dom\Xpath;

$doc = Document::fromLoader(
    Loader\xml_file_loader('data.xml', LIBXML_NOBLANKS, 'UTF-8'),
    Configurator\format_output($debug),
    Configurator\validator(
        Validator\internal_xsd_validator()
    ),
    new MyCustomMergeImportsConfigurator(),
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

* [Assertions](#assertions): Assert if a Node is of a specific type.
* [Builders](#builders): Let you build XML by using a declarative API.
* [Collection](#collection): A wrapper for dealing with lists of nodes.
* [Configurators](#configurators): Specify how you want to configure your DOM document.
* [Loaders](#loaders): Determine where the XML should be loaded from.
* [Locators](#locators): Enables you to locate specific XML elements.
* [Manipulators](#manipulators): Allows you to manipulate any DOM document.
* [Mappers](#mappers): Converts the DOM document to something else.
* [Predicates](#predicates): Check if a Node is of a specific type.
* [Traverser](#traverser): Traverse over a complete DOM tree and perform visitor-based manipulations.
* [Validators](#validators): Validate the content of your XML document.
* [XPath](#xpath): Query for specific elements based on XPath queries.


## Assertions

Assert if a Node is of a specific type.

#### assert_attribute

Assert if a node is of type `Dom\Attr`.

```php
use Psl\Type\Exception\AssertException;
use function VeeWee\Xml\Dom\Assert\assert_attribute;

try {
    assert_attribute($someNode)
} catch (AssertException $e) {
    // Deal with it
}
```

#### assert_cdata

Assert if a node is of type `Dom\CDATASection`.

```php
use Psl\Type\Exception\AssertException;
use function VeeWee\Xml\Dom\Assert\assert_cdata;

try {
    assert_cdata($someNode)
} catch (AssertException $e) {
    // Deal with it
}
```

#### assert_document

Assert if a node is of type `Dom\XMLDocument`.

```php
use Psl\Type\Exception\AssertException;
use function VeeWee\Xml\Dom\Assert\assert_document;

try {
    assert_document($someNode)
} catch (AssertException $e) {
    // Deal with it
}
```

#### assert_dome_node_list

Assert if a variable is of type `Dom\NodeList`.

```php
use Psl\Type\Exception\AssertException;
use function VeeWee\Xml\Dom\Assert\assert_dom_node_list;

try {
    assert_dom_node_list($someVar)
} catch (AssertException $e) {
    // Deal with it
}
```

#### assert_element

Assert if a node is of type `Dom\Element`.

```php
use Psl\Type\Exception\AssertException;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Assert\assert_element;

$doc = Document::fromXmlFile('some.xml');
$item = $doc->xpath()->query('item')->item(0);

use Psl\Type\Exception\AssertException;
use function VeeWee\Xml\Dom\Assert\assert_document;

try {
    assert_element($someNode)
} catch (AssertException $e) {
    // Deal with it
}
```

## Builders

Lets you build XML by using a declarative API.

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

Operates on a `Dom\Element` and adds the attribute with specified key and value

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

Operates on a `Dom\Element` and adds multiple attributes with specified key and value

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

#### cdata

Operates on a `Dom\Node` and creates a `Dom\CDATASection`.
It can contain a set of configurators that can be used to dynamically change the cdata's contents.

```php
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\cdata;
use function VeeWee\Xml\Dom\Builder\children;

element('hello', children(
    cdata('<html>world</html>')
));
```

```xml
<hello><![CDATA[<html>world</html>]]></hello>
```

#### children

Operates on a `Dom\Node` and attaches multiple child nodes.

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

Operates on a `Dom\Node` and creates a new element.
It can contain a set of configurators that can be used to specify the attributes, children, value, ... of the element.

```php
use function VeeWee\Xml\Dom\Builder\element;

element('hello', ...$configurators);
```

```xml
<hello />
```

#### escaped_value

Operates on a `Dom\Element` and sets the node value.
All XML entities `<>"'` will be escaped.

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\escaped_value;

element('hello', escaped_value('<"\'>'));
```

```xml
<hello>&lt;&quot;&apos;&gt;</hello>
```


#### namespaced_attribute

Operates on a `Dom\Element` and adds a namespaced attribute with specified key and value

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

#### namespaced_attributes


Operates on a `Dom\Element` and adds a namespaced attribute with specified key and value

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

Operates on a `Dom\Node` and creates a new namespaced element.
It can contain a set of configurators that can be used to specify the attributes, children, value, ... of the element.

```php
use function VeeWee\Xml\Dom\Builder\namespaced_element;

namespaced_element('http://acme.com', 'hello', ...$configurators);
```

```xml
<hello xmlns="http://acme.com" />
```

#### nodes

Operates on a `Dom\XMLDocument` and is the builder that is being called by the `Document::manipulate` method.
It can return one or more `Dom\Node` objects

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\nodes;

nodes(
    element('item'),
    static fn (XMLDocument $document): array => [
        element('item')($document),
        element('item')($document),
    ],
    element('item'),
    element('item')
)($document);
```

#### value

Operates on a `Dom\Element` and sets the node value.

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\value;

element('hello', value('world'));
```

#### xmlns_attribute

Operates on a `Dom\Element` and adds a xmlns namespace attribute.

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;

element('hello', xmlns_attribute('ns', 'http://ns.com'));
```

#### xmlns_attributes

Operates on a `Dom\Element` and adds multiple xmlns namespace attributes.

```php
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\xmlns_attributes;

element('hello', xmlns_attributes(['ns' => 'http://ns.com']));
```

```xml
<hello>world</hello>
```
## Collection

This package provides a type-safe replacement for `Dom\NodeList` with few more options.
Some examples:

```php
use Dom\Element;
use Psl\Type;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Locator\Node\value;

$totalPrice = NodeList::fromNodeList($list)
    ->expectAllOfType(Element::class)
    ->filter(fn(Element $element) => $element->nodeName === 'item')
    ->eq(0)
    ->siblings()
    ->children()
    ->query('./price')
    ->reduce(
        static fn (int $total, Element $price): int
            => $total + value($price, Type\int()),
        0
    );
```

Most of the functions on the NodeList class are straight forward and documented elsewere.
Feel free to scroll through or let your IDE autocomplete the class to find out what is inside there!

## Configurators

Specify how you want to configure your DOM document.

#### canonicalize

The loader runs canonicalization (C14N) on the document and applies some other optimalizations like cdata stripping and basic namespace optimizations.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\canonicalize;

Document::fromXmlString(
    $xml,
    canonicalize()
);
```

#### comparable

The loader runs following optimization on the provided XML, in order to make it comparable:

* [Namespace optimizations](#optimize_namespaces)
* [Canonicalization](#canonicalize)
* [Attribute sorting](#sortattributes)

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\comparable;

Document::fromXmlFile(
    $file,
    comparable()
);
```

#### document_uri

Allows you to keep track of the document uri, even if you are using an in-memory string.
Internally, it sets `Dom\XMLDocument::$documentURI`, which gets used as `file` in the [error-handling issues component](./error-handling.md#issues).

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\document_uri;

$wsdl = 'http://myservice.com?wsdl';
Document::fromXmlString(
    $loadFromHttp($wsdl),
    document_uri($wsdl)
);
```

#### format_output

Specify if the saved XML output should be formatted or not.
This can make the output of the DOM document human-readable or with trimmed spaces.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\format_output;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;

$debug = true;
$doc = Document::fromLoader(
    // If the input has blank nodes, You'll need to use LIBXML_NOBLANKS in order to change the output format. 
    xml_file_loader('data.xml', LIBXML_NOBLANKS)
    format_output($debug),
);
```

#### normalize

This configurator normalizes an XML file to return the XMLDocument back in a "normal" form.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\normalize;

Document::fromUnsafeDocument(
    $document,
    normalize()
);
```

#### optimize_namespaces

This configurator detects all and renames all namespaces in order to optimize them.
The optimasation itself, must be triggered by using a load function with `LIBXML_NSCLEAN`.
This optimization is included in the `comparable()` and `canonicalize()` configurator.


```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\optimize_namespaces;

Document::fromUnsafeDocument(
    $document,
    optimize_namespaces('prefix')
);
```

#### pretty_print

Makes the output of the DOM document human-readable.
This reloads the DOM document and reformats the nodes.
Consider using [format_output](#format_output) instead if you don't want to re-load the XML document.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\pretty_print;

$doc = Document::fromXmlFile(
    'data.xml',
    pretty_print(),
);
```

#### traverse

Takes a list of [Visitors](#visitors) as argument and [traverses](#traverse) over de DOM tree.
The visitors can be used to do DOM manipulations.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor;
use function VeeWee\Xml\Dom\Configurator\traverse;

$doc = Document::fromXmlFile(
    $file,
    traverse(
        new Visitor\SortAttributes(),
    )
);
```

#### trim_spaces

Trims all whitespaces from the DOM document in order to make it as small as possible in bytesize.
This reloads the DOM document and reformats the nodes.
Consider using [format_output](#format_output) instead if you don't want to re-load the XML document.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\trim_spaces;

$doc = Document::fromXmlFile(
    'data.xml',
    trim_spaces(),
);
```

#### utf8

Marks the DOM document as UTF-8.
There are 2 ways to do this: either whilst loading or afterward through a configurator.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;

$doc = Document::fromLoader(
    xml_file_loader('data.xml', override_encoding: 'UTF-8'),
    utf8(),
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

A configurator can be any `callable` that takes a `Dom\XMLDocument` and configures it:

```php
namespace VeeWee\Xml\Dom\Configurator;

use \Dom\XMLDocument;

interface Configurator
{
    public function __invoke(XMLDocument $document): XMLDocument;
}
```

You can apply the configurator as followed:

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Configurator;

// On an empty XML document
$document = Document::configure(...$configurators);

// On an existing XML document.
$document = Document::fromLoader($loader, ...$configurators);
```

## Loaders

#### xml_file_loader

Loads an XML document from a file.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;

$doc = Document::fromXmlFile('some-xml.xml', ...$configurators);

// or

$doc = Document::fromLoader(xml_file_loader($file, options: LIBXML_NOCDATA, override_encoding: 'UTF-8'));
```

#### xml_node_loader

Loads an XML document from an external `Dom\Node`.

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlNode($someExternalNode, ...$configurators);
```

#### xml_string_loader

Loads an XML document from a string.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

$doc = Document::fromXmlString('<xml />', ...$configurators);

// or

$doc = Document::fromLoader(xml_string_loader($xml, options: LIBXML_NOCDATA, override_encoding: 'UTF-8'));
```

#### Writing your own loader

```php
namespace VeeWee\Xml\Dom\Loader;

use \Dom\XMLDocument;

interface Loader
{
    public function __invoke(): XMLDocument;
}
```

You can apply the loader as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::fromLoader($loader, ...$configurators);
```

## Locators

Locators can be used to search for specific elements inside your DOM document.
The locators are split up based on what they are locating.

### Attribute

The attributes locators will return attributes and can be called on a `Dom\Node`.

#### attributes_list

This function will look for all attributes on a `Dom\Node`.
For nodes that don't support attributes, you will receive an empty `NodeList`.
The result of this function will be of type `NodeList<\Dom\Attr>`.

```php
use Dom\Attr;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;

$attributes = attributes_list($element)->sort(
    static fn (Attr $a, Attr $b): int => $a->nodeName <=> $b->nodeName
);
```

#### xmlns_attributes_list

This function will look for all xmlns attributes on a `Dom\Node`.
For nodes that don't support attributes, you will receive an empty `NodeList`.
The result of this function will be of type `NodeList<Dom\Attr>`.

```php
use Dom\Attr;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;

$attributes = xmlns_attributes_list($element)->sort(
    static fn (Attr $a, Attr $b): int => $a->prefix <=> $b->prefix
);
```

### Document

The Document locators can be called directly from the `Document` class.
It will return the root document element of the provided XML.

#### document_element

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Locator\document_element;

$doc = Document::fromXmlFile('some.xml');
$rootElement = $doc->locate(document_element());

// Since this is a common action, there is also a shortcut:
$doc->locateDocumentElement();
```

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

These locators can be run on `Dom\Element` instances.

#### Element\ancestors

Fetch all ancestor elements from a specific `Dom\Node`.

```php
use function VeeWee\Xml\Dom\Locator\Element\ancestors;

$ancestorNodes = ancestors($element);
```

#### Element\children

Fetch all child `Dom\Element`'s from a specific `Dom\Node`.
If you only want all types of children (`Dom\Text`, ...), you can use the `Node\children()` locator.

```php
use function VeeWee\Xml\Dom\Locator\Element\children;

$childElements = children($element);
```

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

#### Element\parent_element

```php
use function VeeWee\Xml\Dom\Locator\Element\parent_element;

$products = parent_element($element);
```

#### Element\siblings

Fetch all sibling elements from a specific `Dom\Node`.

```php
use function VeeWee\Xml\Dom\Locator\Element\siblings;

$ancestorNodes = siblings($element);
```

### Node

These locators can be run on any `Dom\Node` instance.

#### Node\children

Fetch all child nodes from a specific `Dom\Node`. This can be any kind of node: `Dom\Text`, `Dom\Element`, ...
If you only want the element children, you can use the `Element\children()` locator.

```php
use function VeeWee\Xml\Dom\Locator\Node\children;

$childNodes = children($element);
```

#### Node\detect_document

Fetch the `Dom\XMLDocument` to which a node is linked.
If the node is not linked to a document yet, it throws a `InvalidArgumentException`.

```php
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

$document = detect_document($element);
```

#### Node\value

Fetch the value from the provided `Dom\Node` and coerce it to a specific type.

```php
use Psl\Type;
use function VeeWee\Xml\Dom\Locator\Node\value;

$productPrice = value($product, Type\float());
```

### Xmlns

These locators can be run on `Dom\Node` instances.

#### Xmlns\linked_namespaces

This function returns a list of all namespaces that are linked to a specific DOM node.

```php
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Locator\Xmlns\linked_namespaces;

/** @var list<\Dom\NamespaceInfo> $namespaces
$namespaces = linked_namespaces($element);
```

#### Xmlns\recursive_linked_namespaces

This function returns a list of all namespaces that are linked to a specific DOM node and all of its children.

```php
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Locator\Xmlns\recursive_linked_namespaces;

/** @var list<\Dom\NamespaceInfo> $namespaces
$namespaces = recursive_linked_namespaces($element);
```

### Xsd

Locates internally applied XSD schema's from a specific `Dom\XMLDocument`.

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

#### optimize_namespaces

```php
use \Dom\XMLDocument;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Manipulator\Document\optimize_namespaces;

$doc = Document::empty();
$doc->manipulate(
    static function (XMLDocument $document): void {
        optimize_namespaces($document, 'prefix');
    }
);
```

### Element

Element specific manipulators operate on `Dom\Element` instances.

#### copy_named_xmlns_attributes

Makes it possible to copy all names xmlns attributes from one element to an other element.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Manipulator\Element\copy_named_xmlns_attributes;

$doc = Document::fromXmlString(
    <<<EOXML
    <root>
        <a xmlns:foo="http://foo" />
        <b />
    </root>
    EOXML
);

$a = $doc->xpath()->querySingle('//a');
$b = $doc->xpath()->querySingle('//b');

copy_named_xmlns_attributes($b, $a);

// > $b will contain xmlns:foo="http://foo"
```

### Node

Node specific manipulators operate on `Dom\Node` instances.

#### append_external_node

Makes it possible to append a `Dom\Node` from an external document into a `Dom\Node` from the current document.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\append_external_node;

$copiedNode = append_external_node($documentNode, $externalNode);
```

#### import_node_deeply

Makes it possible to import a full `Dom\Node` from an external document so that it can be used in the current document.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\import_node_deeply;

$copiedNode = import_node_deeply($documentNode, $externalNode);
```

#### remove

Makes it possible to remove any type of `Dom\Node` directly. This include attributes.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\remove;

$removedNode = remove($node);
```
#### rename

Makes it possible to rename `Dom\Element` and `Dom\Attr`nodes.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\rename;

rename($node, 'foo');
rename($node, 'a:foo');
```

Internally, this function uses an element and attribute rename functionality, which can also be used standalone:

```php
use function VeeWee\Xml\Dom\Manipulator\Attribute\rename as rename_attribute;
use function VeeWee\Xml\Dom\Manipulator\Element\rename as rename_element;

rename_attribute($attr, 'foo', $newUri);
rename_element($element, 'foo', $newUri);
```

Besides renaming attributes and elements, you can also rename an xmlns namespace.
This however, operates on the `Dom\XMLDocument`:

```php
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename;

rename($doc, 'http://namespace', 'prefix');
```

#### remove_namespace

Makes it possible to remove a xmlns `Dom\Attr` from an element.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\remove_namespace;

$removedNamespace = remove_namespace($namespace, $element);
```

#### replace_by_external_node

Makes it possible to replace a `Dom\Node` from the current document with a `Dom\Node` from an external document.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_node;

$copiedNode = replace_by_external_node($documentNode, $externalNode);
```

#### replace_by_external_nodes

Makes it possible to replace a `Dom\Node` from the current document with a list of `Dom\Node` from an external document.

```php
use function VeeWee\Xml\Dom\Manipulator\Node\replace_by_external_nodes;

$copiedNode = replace_by_external_nodes($documentNode, $externalNodes);
```

## Mappers

Converts the DOM document to something else.

#### xml_string

```php
use VeeWee\Xml\Dom\Document;

$doc = Document::fromXmlFile('some.xml');

// Get full XML including the XML declaration tag:
$xml = $doc->toXmlString();

// OR, get only the XML part without declaration:
$xml = $doc->stringifyDocumentElement();
```

Instead of mapping a full document, you can also map a specific node only to XML.

```php
use function VeeWee\Xml\Dom\Mapper\xml_string;

$mapper = xml_string();
$xml = $mapper($someNode);

// Or use the shortcut on Document level:
$xml = $doc->stringifyNode($someNode);
```

#### xslt_template

Allows you to map an XML document based on an [XSLT template](xslt.md).

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\xslt_template;

$doc = Document::fromXmlFile('data.xml');
$xslt = Document::fromXmlFile('xml-to-yaml-converter.xslt');

echo $doc->map(xslt_template($xslt, ...$processorConfigurators));
```

For more information on the processor configurators, [see the XSLT documentation](xslt.md#configurators); 

#### Writing your own mapper


A configurator can be any `callable` that takes a `Dom\XMLDocument` and configures it:

```php
namespace VeeWee\Xml\Dom\Mapper;

use \Dom\XMLDocument;

/**
 * @template R
 */
interface Mapper
{
    /**
     * @return R
     */
    public function __invoke(XMLDocument $document): mixed;
}

```

You can apply the mapper as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::fromXmlFile('some.xml');
$result = $document->map($mapper);
```

## Predicates

Check if a Node is of a specific type.

#### is_attribute

Checks if a node is of type `Dom\Attr`.

```php
use function VeeWee\Xml\Dom\Predicate\is_attribute;

if (is_attribute($someNode)) {
   // ...
}
```

#### is_cdata

Checks if a node is of type `Dom\CDATASection`.

```php
use function VeeWee\Xml\Dom\Predicate\is_cdata;

if (is_cdata($someNode)) {
   // ...
}
```

#### is_default_xmlns_attribute

Checks if a node is of type `Dom\Attr` and is the default xmlns.

```php
use function VeeWee\Xml\Dom\Predicate\is_default_xmlns_attribute;

if (is_default_xmlns_attribute($namespace)) {
   // ...
}
```

#### is_document

Checks if a node is of type `Dom\XMLDocument`.

```php
use function VeeWee\Xml\Dom\Predicate\is_document;

if (is_document($someNode)) {
   // ...
}
```

#### is_document_element

Checks if a node is the root `Dom\Element` of  the `Dom\XMLDocument`.

```php
use function VeeWee\Xml\Dom\Predicate\is_document_element;

if (is_document_element($rootNode)) {
   // ...
}
```

#### is_element

Checks if a node is of type `Dom\Element`.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Predicate\is_element;

$doc = Document::fromXmlFile('some.xml');
$item = $doc->xpath()->query('item')->item(0);

if (is_element($item)) {
   // ...
}
```

#### is_non_empty_text

Checks if a node is of type `Dom\Text` and that its value is not just a whitespace.
This behaves in the opposite  way of the `is_whitespace()` function and uses the `is_text()` function internally.

```php
use function VeeWee\Xml\Dom\Predicate\is_non_empty_text;

if (is_non_empty_text($someNode)) {
   // ...
}
```

#### is_text

Checks if a node is of type `Dom\Text`.
You can also check for `is_whitespace()` or `is_non_empty_text()` if you want to do a deeper check.

```php
use function VeeWee\Xml\Dom\Predicate\is_text;

if (is_text($someNode)) {
   // ...
}
```

#### is_xmlns_attribute

Checks if a node is of type `Dom\Attr`.

```php
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

if (is_xmlns_attribute($namespace)) {
   // ...
}
```

#### is_whitespace

Checks if a node is of type `Dom\Text` and that its value consists of just whitespaces.
This behaves in the opposite  way of the `is_non_empty_text()` function and uses the `is_text()` function internally.

```php
use function VeeWee\Xml\Dom\Predicate\is_whitespace;

if (is_whitespace($someNode)) {
   // ...
}
```

## Traverser

Traverse over a complete DOM tree and perform visitor-based manipulations.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor;

$doc = Document::fromXmlFile($file);
$doc->traverse(new Visitor\SortAttributes());
```

The traverse method will iterate over every node in the complete DOM tree.
It takes a list of visitors that will be triggered for every node.
The visitors allow you to look for specific things and for example replace them with something else.


### Visitors

Imagine you want to replace all attribute values in all XML tags with 'wazzup'.
Here is an example visitor that implements this feature:

```php
use Dom\Node;
use VeeWee\Xml\Dom\Traverser\Action;
use VeeWee\Xml\Dom\Traverser\Visitor\AbstractVisitor;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Predicate\is_attribute;

class WazzupVisitor extends AbstractVisitor
{
    public function onNodeLeave(Node $node) : Action
    {
        if (!is_attribute($node)) {
            return new Action\Noop();
        }

        attribute($node->nodeName, 'WAZZUP')($node->parentNode);

        return new Action\Noop();
    }
}
```

So how does it work? Every time the traverser sees a Node, it will trigger all provided visitors.
The visitor above will look for attribute nodes. All other nodes will be ignored.
Next it will replace the attribute with value WAZZUP and add it to the node.
Finally, we tell the traverser that the visitor is finished and no additional actions need to be performed.

Here is a list of built-in visitors that can be used as a base to build your own.

#### SortAttributes

The SortAttributes visitor will sort all element's attributes in alphabetic order.
This makes XML easier to compary by using a diff tool.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor;

$doc = Document::fromXmlFile($file);
$doc->traverse(new Visitor\SortAttributes());
```

#### RemoveNamespaces

Because life is too short, sometimes you don't want to deal with namespaces...
This visitor removes all xmlns declarations and prefixes from your XML document.
This can also be used in combination with `xml_decode`, which will result in an unprefixed result.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor;

$doc = Document::fromXmlFile($file);
$doc->traverse(new Visitor\RemoveNamespaces());
```

This visitor allows some additional configurations:

* `RemoveNamespaces:all()`: Removes all namespaces by default.
* `RemoveNamespaces::prefixed()`: Removes all namespaces with a prefix (`xmlns:prefix=""`).
* `RemoveNamespaces::unprefixed()`: Removes all namespaces without a prefix (`xmlns=""`).
* `RemoveNamespaces::byPrefixNames(['a', 'b'])`: Removes all namespaces with a specific prefix.
* `RemoveNamespaces::byNamespaceURIs(['http://xxx'])`: Removes all namespaces with a specific namespace URI.
* `new RemoveNamespaces($yourFilter)`: If you want to apply a custom filter to select the namespaces you allow to be stripped.


#### Building your own visitor

A visitor needs to implement the visitor interface.
It contains both an `onNodeEnter` and an `onNodeLeave` method.
The result of these functions is an action that can be performed by the traverser:

```php
namespace VeeWee\Xml\Dom\Traverser;

use Dom\Node;

interface Visitor
{
    public function onNodeEnter(Node $node): Action;
    public function onNodeLeave(Node $node): Action;
}
```

An action looks like this:

```php
namespace VeeWee\Xml\Dom\Traverser;

use Dom\Node;

interface Action
{
    public function __invoke(Node $currentNode): void;
}
```

Finally, here is a list of built-in actions:

* `Noop`: This tells the traverser that no additional action needs to be executed.
* `ReplaceNode($newNode)`: Can be used to replace a node with another one. 
* `RemoveNode`: Can be used to remove the node from the XML tree.
* `RenameNode`: Can be used to rename the node.


## Validators

Validate the content of your XML document.

#### internal_xsd_validator

Validates the document based on all internally specified XML schema's.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xsd\Schema\Manipulator;
use function VeeWee\Xml\Dom\Validator\internal_xsd_validator;

$doc = Document::fromXmlFile('some.xml');
$issues = $doc->validate(internal_xsd_validator(
    Manipulator\base_path('/var/www')
));
```

It takes one or more XSD schema manipulators. For more information [see XSD schema manipulators](xsd.md#schema-manipulators).

#### validator_chain

Can be used to validate with multiple validators. The result is a combined list of issues!

```php
use VeeWee\Xml\Dom\Document;
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
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Validator\xsd_validator;

$doc = Document::fromXmlFile('some.xml');
$issues = $doc->validate(xsd_validator('myown.xsd'));
```

#### Writing your own validator

A validator can be any `callable` that takes a `Dom\XMLDocument` and returns an `IssueCollection`.

```php
namespace VeeWee\Xml\Dom\Validator;

use \Dom\XMLDocument;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;

interface Validator
{
    public function __invoke(XMLDocument $document): IssueCollection;
}
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
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Xpath\Configurator\all_functions;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(all_functions());
```

#### functions

Registers a list of known PHP functions to the XPath object, allowing you to use `php:somefunction()` inside your XPath query.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Xpath\Configurator\functions;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(functions(['has_multiple' => has_multiple(...)]));
```

#### namespaced_functions

Registers a list of namespaced functions to the XPath object, allowing you to use `prefix:somefunction()` inside your XPath query.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Xpath\Configurator\namespaced_functions;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(namespaced_functions('http://ns', 'prefix', ['has_multiple' => has_multiple(...)]));
```

#### namespaces

Registers a map of namespaces with their prefix to the XPath object. 
This allows you to use a prefix name for a specific namespace. E.g. `//soap:envelope`.

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Xpath\Configurator\namespaces;

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
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Xpath\Configurator\php_namespace;

$doc = Document::fromXmlFile('data.xml');
$xpath = $doc->xpath(php_namespace());
```

#### Writing your own XPath configurator

A configurator can be any `callable` that takes a `Dom\XPath` and configures it:

```php
namespace VeeWee\Xml\Dom\Xpath\Configurator;

use Dom\XPath;

interface Configurator
{
    public function __invoke(XPath $xpath): XPath;
}
```

You can apply the XPath configurator as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::fromXmlFile('some.xml');
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

Run a specific XPath query and expect to get back a list of matching `Dom\Element`.

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

An XPath locator can be any `callable` that takes a `Dom\XPath` and locates something on it:


```php
namespace VeeWee\Xml\Dom\Xpath\Locator;

use Dom\XPath;

/**
 * @template T
 */
interface Locator
{
    /**
     * @return T
     */
    public function __invoke(XPath $xpath): mixed;
}
```

You can apply the locator as followed:

```php
use VeeWee\Xml\Dom\Document;

$document = Document::fromXmlFile('some.xml');
$xpath = $document->xpath();

$result = $xpath->locate($locator);
```
