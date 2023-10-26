# Encoding Component

This components provides functions like `encode()` and `decode()` so that you can deal with XML just like you deal with JSON!
By using these functions there is no need for ever using the `SimpleXML` library again!

## Example

```php
use VeeWee\Xml\Dom\Traverser\Visitor\RemoveNamespaces;
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Dom\Configurator\traverse;
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\Dom\Validator\xsd_validator;
use function VeeWee\Xml\Encoding\xml_decode;
use function VeeWee\Xml\Encoding\xml_encode;

$data = xml_decode(
    file_get_contents('file.xml'),
    validator(xsd_validator('some-schema.xsd')),
    traverse(new RemoveNamespaces())
);

// You can read or change the data
$data['root']['name'] = 'MyName';

// And finally convert it back to XML!
$xml = xml_encode($data, utf8(), pretty_print());
```

The encoding components consist out of following functions

- [document_encode](#document_encode): Encodes an array into a DOM [Document](dom.md).
- [element_decode](#element_decode): Decodes a DOMElement into an array.
- [element_encode](#element_encode): Encodes an array into an XML string, as a partial document
- [typed](#typed): Decodes an XML string into a defined well-typed shape.
- [xml_decode](#xml_decode): Decodes an XML string into an array
- [xml_encode](#xml_encode): Encodes an array into an XML string, as a full document

More information about [the PHP format can be found here](#php-format).

## Functions

#### document_encode

The `document_encode` function transforms an array into a DOM [Document](dom.md).
This way, you can make some changes before transforming it ack to an XML string.

```php
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Encoding\document_encode;

$doc = document_encode(['hello' => 'world'], utf8(), pretty_print());

// Do whatever DOM manipulation you want before transforming it back to an XML string.

```

The first argument is the data structure.
The component does data normalization for you so that you can pass in any custom types like iterables, json serializable objects, ...

Besides the data, you can apply any [DOM configurator](dom.md#configurators) you please.
In the example above, we tell the DOM document to be UTF8 and pretty printed.

More information about [the PHP format can be found here](#php-format).

#### element_decode

Decode a DOMElement into an array.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Dom\Traverser\Visitor\RemoveNamespaces;
use function VeeWee\Xml\Dom\Configurator\traverse
use function VeeWee\Xml\Encoding\element_decode;

$doc = Document::fromXmlFile($file);
$element = $doc->xpath()->querySingle('//catalog:items/products[0]/item')

$data = element_decode($element, traverse(new RemoveNamespaces()));

// $data = ['name' => 'x', 'category' => 'z'];
```

Works similar to `xml_encode`.
It takes any DOMElement as input and transforms this element to a decoded XML array.
This way, you can use the DOM Document as a starting base to query data, but transform a resulting XML element into an array.

:exclamation: Since ext-dom reimports the node into a fresh document, namespaces might have been moved on a parent element.

Besides the element, you can apply any [DOM configurator](dom.md#configurators) you please.
In the example above, we run an XSD validator before parsing the XML into an array!

More information about [the PHP format can be found here](#php-format).

#### element_encode

Encode transforms an array into an XML string, as a partial document. For encoding to a full document, use [xml_encode](#xml_encode).

```php
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Encoding\element_encode;

$xmlString = element_encode(['hello' => 'world'], pretty_print());

// $xmlString = '<hello>world</hello>';
```

The first argument is the data structure.
The component does data normalization for you so that you can pass in any custom types like iterables, json serializable objects, ...

Besides the data, you can apply any [DOM configurator](dom.md#configurators) you please.
In the example above, we tell the DOM document to be pretty printed.

If you need to generate a full document with xml header, you should use the function `xml_encode`.

More information about [the PHP format can be found here](#php-format).

#### xml_decode

Decode transforms an XML string into an array.

```php
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\Dom\Validator\xsd_validator;
use function VeeWee\Xml\Encoding\xml_encode;

$data = xml_decode('<hello>world</hello>', validator(xsd_validator('schema.xsd')));

// $data = ['hello' => 'world'];
```

The first argument is the XML data.
The result will be a nested array. The data inside the array will all be strings, because XML doesn't really know about types.
If you want to transform the types to something more useable, you can use the [typed](#typed) function. 

Besides the data, you can apply any [DOM configurator](dom.md#configurators) you please.
In the example above, we run an XSD validator before parsing the XML into an array!

More information about [the PHP format can be found here](#php-format).

#### xml_encode

Encode transforms an array into an XML string, as a full document. For encoding to a partial document, use [element_encode](#element_encode).

```php
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Encoding\xml_encode;

$xmlString = xml_encode(['data' => ['hello' => 'world']], utf8(), pretty_print());

/* $xmlString = '<?xml version="1.0" encoding="UTF-8"?><data><hello>world</hello></data>'; */
```

The first argument is the data structure.
The component does data normalization for you so that you can pass in any custom types like iterables, json serializable objects, ...

Besides the data, you can apply any [DOM configurator](dom.md#configurators) you please.
In the example above, we tell the DOM document to be UTF8 and pretty printed. 

This function will transform the data to a full xml document string. If you need to render a partial xml document without xml header, you can use the function `element_encode`. 

More information about [the PHP format can be found here](#php-format).

#### typed

Since xml does not have any notion of types, it transforms all values to strings.
To convert it to the types you want, you can use the `typed()` function that converts the strings to the correct types for you:


```php
use function Psl\Type\int;
use function Psl\Type\shape;
use function Psl\Type\string;
use function Psl\Type\vector;
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\Dom\Validator\xsd_validator;
use function VeeWee\Xml\Encoding\typed;

$data = typed(
    <<<EOXML
        <root>
           <item>
               <id>1</id>
               <name>X</name>
               <category>A</category>
               <category>B</category>
               <category>C</category>
           </item>     
        </root>
    EOXML,
    shape([
        'root' => shape([
            'item' => shape([
                'id' => int(),
                'name' => string(),
                'category' => vector(string()),
            ])
        ])
    ]),
    validator(xsd_validator('some-schema.xsd'))
);
```

It works exactly the same as the [xml_decode](#xml_decode) function but with an additional type parameter.
Structuring the shape of the type-result is done by the [azjezz/psl](https://github.com/azjezz/psl) package.

In modern systems, you might find the need to convert XML data to DTO objects.
You can do so by using the typed class as well:

```php
use function Psl\Type\converted;
use function Psl\Type\int;
use function Psl\Type\instance_of;
use function Psl\Type\shape;
use function Psl\Type\string;
use function Psl\Type\vector;
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\Dom\Validator\xsd_validator;
use function VeeWee\Xml\Encoding\typed;

class MyDto {
    public function __construct(
        public readonly int $id;   
        public readonly string $name;   
        /** @var list<string> */
        public readonly array $categories;   
    ) {
    }
}

$myValidatedDto = typed(
    <<<EOXML
        <root>
           <item>
               <id>1</id>
               <name>X</name>
               <category>A</category>
               <category>B</category>
               <category>C</category>
           </item>     
        </root>
    EOXML,
    converted(
      shape([
          'root' => shape([
              'item' => shape([
                  'id' => int(),
                  'name' => string(),
                  'category' => vector(string()),
              ])
          ])
      ]),
      instance_of(MyDto::class),
      fn (array $data): MyDto => MyDto::create(
        $data['root']['item']['id'],
        $data['root']['item']['name'],
        $data['root']['item']['category'],
      )
    ),
    validator(xsd_validator('some-schema.xsd'))
);
```

More information about [the PHP format can be found here](#php-format).

## PHP Format

```php
[
    'root' => [
        '@namespaces' => [
            '' => 'http://rooty.root',
            'test' => 'http://testy.test',
        ],
        'test:item' => [
            [
                '@attributes' => [
                    'id' => 1,
                    'test:type' => 'hello'  
                ],
                '@value' => 'world'
            ],
            [
                '@attributes' => [
                    'id' => 2,
                    'test:type' => 'hello'  
                ],
                '@cdata' => '<html>Moon</html>'
            ]
        ]
    ]
];
```

- Every XML file has exactly 1 names root element.
- The `@namespaces` section contains the `xmlns:` attributes to declare namespaces.
  - Every element can contain a namespaces section.
  - The key provides the alias for the namespace, which can be used as to prefix both an attribute and element name.
  - The value contains the namespace URI.
  - Be default, encoding will optimize namespace imports for you!
- The `@attributes` section contains key-value pairs of all element attributes.
  - Every element can contain an attributes section.
  - You can prefix an attribute name with a namespace prefix: `prefix:name`.
  - You can provide any value that can be coerced to a string as an element value.
- The `@value` section can be used for an element that contains both attributes and a text value.
    - You can provide any value that can be coerced to a string as an element value.
    - If the element does not have attributes, you can directly pass the value to the element name. In that case there is no need for the `@value` section.
    - All XML entities `<>"'` will be encoded before inserting a value into XML.
- The `@cdata` section can be used for escaping HTML/XML content inside your XML element.
  - You can provide any value that can be coerced to a string as an element value.
  - The content will be wrapped with `<[CDATA[ ]]>`, special XML entities will not be escaped
  - During decoding, cdata information will get lost: the element's `@value` will contain escaped XML entities. This is because an element can contain a mix of cdata and text nodes.
- You can nest a single element or an array of elements into a parent element.

#### Decoded types
Decoding always returns the XML values as strings.
You can choose to use the [typed](#typed) function to transform those strings into the types you want.

#### Encoded types
Whilst encoding the array back to a string, the encoder tries to transform the provided values into a string.
Currently, we support following transformations in this order:

- `VeeWee\Xml\Encoding\XmlSerializable`: Transforms objects to a normalizable value by using the `xmlSerialize()` function.
- `iterables`: Any iterable will be converted to an array first.
- `JsonSerializable`: Transforms objects to arrays by using the `jsonSerialize()` function.
- `Stringable`: Transforms objects by using the `__toString()` function.
- `Psl\Type\string()->coerce()`: For transforming any scalar types. 

The XmlSerializable interface looks like this:

```php
namespace VeeWee\Xml\Encoding;

/**
 * @psalm-type SupportedValue=scalar|\Stringable|\JsonSerializable|XmlSerializable
 */
interface XmlSerializable
{
    /**
     * Transform an object into a value for making it XML serializable.
     * The iterable version can contain nested XmlSerializable objects.
     *
     * @return SupportedValue|iterable<SupportedValue>
     */
    public function xmlSerialize (): mixed;
}
```
