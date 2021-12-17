<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Manipulator\Element;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Manipulator\Element\copy_named_xmlns_attributes;
use function VeeWee\Xml\Dom\Mapper\xml_string;
use function VeeWee\Xml\Dom\Xpath\Configurator\namespaces;

final class CopyNamedXmlnsAttributesTest extends TestCase
{
    public function test_it_can_copy_names_xmlns_attributes_in_the_same_doc(): void
    {
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

        static::assertXmlStringEqualsXmlString('<b xmlns:foo="http://foo" />', xml_string()($b));
    }

    public function test_it_does_not_copy_root_xmlns(): void
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <root>
                <a xmlns="http://foo" />
                <b />
            </root>
            EOXML
        );

        $a = $doc->xpath(namespaces(['a' => "http://foo"]))->querySingle('//a:a');
        $b = $doc->xpath()->querySingle('//b');

        copy_named_xmlns_attributes($b, $a);

        static::assertXmlStringEqualsXmlString('<b />', xml_string()($b));
    }

    public function test_it_does_not_overwrite_the_same_name(): void
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <root>
                <a xmlns:foo="http://foo" />
                <b xmlns:foo="http://bar" />
            </root>
            EOXML
        );

        $a = $doc->xpath()->querySingle('//a');
        $b = $doc->xpath()->querySingle('//b');

        copy_named_xmlns_attributes($b, $a);

        static::assertXmlStringEqualsXmlString('<b xmlns:foo="http://bar" />', xml_string()($b));
    }

    public function test_it_can_do_many_namespaces_as_well(): void
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <root>
                <a xmlns:foo="http://foo" xmlns:bar="http://bar" xmlns:baz="http://baz" />
                <b />
            </root>
            EOXML
        );

        $a = $doc->xpath()->querySingle('//a');
        $b = $doc->xpath()->querySingle('//b');

        copy_named_xmlns_attributes($b, $a);

        static::assertXmlStringEqualsXmlString('<b xmlns:foo="http://foo" xmlns:bar="http://bar" xmlns:baz="http://baz" />', xml_string()($b));
    }

    public function test_it_does_not_include_internally_added_namespaces(): void
    {
        $doc = Document::fromXmlString(
            <<<EOXML
            <root>
                <a xmlns:foo="http://foo">
                    <barbaz xmlns:bar="http://bar" xmlns:baz="http://baz" />
                </a>
                <b />
            </root>
            EOXML
        );

        $a = $doc->xpath()->querySingle('//a');
        $b = $doc->xpath()->querySingle('//b');

        copy_named_xmlns_attributes($b, $a);

        static::assertXmlStringEqualsXmlString('<b xmlns:foo="http://foo" />', xml_string()($b));
    }

    public function test_it_can_copy_cross_docs(): void
    {
        $doc1 = Document::fromXmlString(
            <<<EOXML
            <root>
                <a xmlns:foo="http://foo">
                    <barbaz xmlns:bar="http://bar" xmlns:baz="http://baz" />
                </a>
            </root>
            EOXML
        );
        $doc2 = Document::fromXmlString(
            <<<EOXML
            <root>
                <b />
            </root>
            EOXML
        );

        $a = $doc1->xpath()->querySingle('//a');
        $b = $doc2->xpath()->querySingle('//b');

        copy_named_xmlns_attributes($b, $a);

        static::assertXmlStringEqualsXmlString('<b xmlns:foo="http://foo" />', xml_string()($b));
    }
}
