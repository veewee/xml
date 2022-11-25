# &lt;XML /&gt;

*XML without worries*

This package aims to provide all tools for dealing with XML in PHP without worries.
You will find a type-safe, declarative API that deals with errors for you!


## Installation

```
composer require veewee/xml
```

## Components

* [DOM](docs/dom.md): Operate on XML documents through the DOM API.
* [Encoding](docs/encoding.md): Provides `xml_encode()` and `xml_decode()` so that you can deal with XML just like you deal with JSON!
* [ErrorHandling](docs/error-handling.md): Provides the tools you need to safely deal with XML.
* [Reader](docs/reader.md): Memory-safe XML reader.
* [Writer](docs/writer.md): Memory-safe XML writer.
* [XSD](docs/xsd.md): Tools for working with XSD schemas.
* [XSLT](docs/xslt.md): Transform XML documents into something else.

## Roadmap

These components are not implemented yet, but have been thought about.
Stay tuned if you want to use these!

* External: [Saxon/C](https://www.saxonica.com/saxon-c/php_api.xml): XSLT 3.0/2.0, XQuery 3.1, XPath 3.1 and Schema Validation 1.0/1.1
  * Awaiting PHP8 support: https://saxonica.plan.io/issues/4842
* ~~External: [XSLT2](https://github.com/genkgo/xsl)~~ (prefer saxon/c)

## About

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/veewee/xml/issues).
Please take a look at our rules before [contributing your code](CONTRIBUTING.md).

### License

veewee/xml is licensed under the [MIT License](LICENSE).
