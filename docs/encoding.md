# Encoding Component

This components provides functions like `encode()` and `decode()` so that you can deal with XML just like you deal with JSON!
By using these functions there is no need for ever using the `SimpleXML` library again!

## Example

```php
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\Dom\Validator\xsd_validator;
use function VeeWee\Xml\Encoding\decode as xml_decode;
use function VeeWee\Xml\Encoding\encode as xml_encode;

$data = xml_decode(
    file_get_contents('file.xml'),
    validator(xsd_validator('some-schema.xsd'))
);

// You can read or change the data
$data['root']['name'] = 'MyName';

// And finally convert it back to XML!
$xml = xml_encode($data, utf8(), pretty_print());
```

The encoding components consist out of following functions

- [decode](#decode): Decodes an XML string into an array
- [encode](#encode): Encodes an array into an XML string
- [typed](#typed): Decodes an XML string into a defined well-typed shape.

## Functions

#### decode

Decode transforms an XML string into an array.

```php
use function VeeWee\Xml\Dom\Configurator\validator;
use function VeeWee\Xml\Dom\Validator\xsd_validator;
use function VeeWee\Xml\Encoding\encode as xml_decode;

$data = xml_decode('<hello>world</hello>', validator(xsd_validator('schema.xsd')));

// $data = ['hello' => 'world'];
```

The first argument is the XML data.
The result will be a nested array. The data inside the array will all be strings, because XML doesn't really know about types.
If you want to transform the types to something more useable, you can use the [typed](#typed) function. 

Besides the data, you can apply any [DOM configurator](dom.md#configurators) you please.
In the example above, we run an XSD validator before parsing the XML into an array!

#### encode

Encode transforms an array into an XML string.

```php
use function VeeWee\Xml\Dom\Configurator\pretty_print;
use function VeeWee\Xml\Dom\Configurator\utf8;
use function VeeWee\Xml\Encoding\encode as xml_encode;

$xml = xml_encode(['hello' => 'world'], utf8(), pretty_print());

// $xml = "<hello>World</hello>";
```

The first argument is the data structure.
The component does data normalization for you so that you can pass in any custom types like iterables, json serializable objects, ...

Besides the data, you can apply any [DOM configurator](dom.md#configurators) you please.
In the example above, we tell the DOM document to be UTF8 and pretty printed. 

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

It works exactly the same as the [decode](#decode) function but with an additional type parameter.
Structuring the shape of the type-result is done by the [https://github.com/azjezz/psl](azjezz/psl) package.
