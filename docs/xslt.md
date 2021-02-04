# XSLT Component

The XSLT component can be used to transform an XML document into something else based on XSLT templates.

## Example

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use VeeWee\Xml\Xslt\Configurator;

$processor = Processor::fromTemplateDocument(
    Document::fromXmlFile('xml-to-yaml-converter.xslt'),
    Configurator\all_functions(),
    Configurator\parameters([
        'name' => 'Jos',    
    ])
);
$doc = Document::fromXmlFile('data.xml');

echo $processor->transformDocumentToString($doc);
```

It consists out of following components:

* [Configurators](#configurators): Specify how you want to process your XSLT templates.
* [Loaders](#loaders): Determine where the load the XSLT template from.
* [Transformers](#transformers): Specify what you want to transform to which kind of output.


## Configurators

Specify how you want to process your XSLT templates.

#### all_functions

Registers all known PHP functions to the XSLTProcessor object, allowing you to use `php:function('ucfirst',string(uid))` inside your XSLT Template.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\all_functions;

$processor = Processor::fromTemplateDocument(
    Document::fromXmlFile('xml-to-yaml-converter.xslt'),
    all_functions()
);
```

#### functions

Registers specific PHP functions to the XSLTProcessor object, allowing you to use `php:function('ucfirst',string(uid))` inside your XSLT Template.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\functions;

$processor = Processor::fromTemplateDocument(
    Document::fromXmlFile('xml-to-yaml-converter.xslt'),
    functions(['ucfirst'])
);
```

#### loader

The loader configurator takes a [loader](#loaders) to specify the source of the XSLT Template.

```php
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\loader;
use function VeeWee\Xml\Xslt\Loader\from_template_document;

$processor = Processor::configure(
    loader(from_template_document($template))
);
```

#### parameters

Registers a map of key/value parameters to the XSLTProcessor which can be used inside your template.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\parameters;

$processor = Processor::fromTemplateDocument(
    Document::fromXmlFile('xml-to-yaml-converter.xslt'),
    parameters([
        'name' => 'Jos'
    ])
);
```

#### profiler

Specify a file in which you want to profile the XSLT processor.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\profiler;

$processor = Processor::fromTemplateDocument(
    Document::fromXmlFile('xml-to-yaml-converter.xslt'),
    profiler('profiler-info.txt')
);
```

#### security_preferences

Specify which [security preferences](https://www.php.net/manual/en/xsltprocessor.setsecurityprefs.php) you want to use inside the XSLT processor. 

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use function VeeWee\Xml\Xslt\Configurator\security_preferences;

$processor = Processor::fromTemplateDocument(
    Document::fromXmlFile('xml-to-yaml-converter.xslt'),
    security_preferences( XSL_SECPREF_DEFAULT )
);
```

## Loaders

Determine where the load the XSLT template from.

#### from_template_document

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;

$template = Document::fromXmlFile('xml-to-yaml-converter.xslt');
$processor = Processor::fromTemplateDocument($template, ...$configurators);
```

## Transformers

Specify what you want to transform to which kind of output.

#### document_to_string

Transforms the input `Document` based on the selected XSLT template into a string.

```php
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;

$template = Document::fromXmlFile('xml-to-yaml-converter.xslt');
$document = Document::fromXmlFile('data.xml');

$processor = Processor::fromTemplateDocument($template);
$text = $processor->transformDocumentToString($document);
```
