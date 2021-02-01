# XSLT Component

The XSLT component can be used to transform an XML document into something else based on XSLT templates.

## Example

```php
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Mapper\xslt_template;

$doc = Document::fromXmlFile('data.xml');
$xsl = Document::fromXmlFile('xml-to-yaml-converter.xslt');

echo $doc->map(xslt_template($xsl));
```



## TODO:

Currently, there is no separate configurable XSLT processor component.
TODO : Provide a real processor component that can be configured with configurators.
Configurators:

* Parameters provider
* Security preferences?
* Register PHP Functions
* Profiling?