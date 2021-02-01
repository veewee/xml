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
use VeeWee\XML\DOM\Xpath;

$doc = Document::fromXmlFile(
    'data.xml',
    Configurator\utf8(),
    Configurator\trim_spaces()
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
## Configurators
## Loaders
## Locators
## Manipulators
## Mappers
## Validators
## XPath
