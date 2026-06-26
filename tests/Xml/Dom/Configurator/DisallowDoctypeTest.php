<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Dom\Configurator;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Exception\DoctypeNotAllowedException;
use function VeeWee\Xml\Dom\Configurator\disallow_doctype;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class DisallowDoctypeTest extends TestCase
{
    public function test_it_rejects_a_bare_doctype(): void
    {
        $this->expectException(DoctypeNotAllowedException::class);

        Document::fromXmlString(
            '<?xml version="1.0"?><!DOCTYPE r><r/>',
            disallow_doctype()
        );
    }

    public function test_it_rejects_an_external_file_entity_doctype(): void
    {
        $this->expectException(DoctypeNotAllowedException::class);

        Document::fromXmlString(
            '<?xml version="1.0"?><!DOCTYPE r [<!ENTITY x SYSTEM "file:///etc/hostname">]><r>&x;</r>',
            disallow_doctype()
        );
    }

    public function test_it_rejects_an_internal_entity_doctype(): void
    {
        $this->expectException(DoctypeNotAllowedException::class);

        Document::fromXmlString(
            '<?xml version="1.0"?><!DOCTYPE r ['
            . '<!ENTITY a "aaaaaaaaaa">'
            . '<!ENTITY b "&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;">'
            . ']><r>&b;</r>',
            disallow_doctype()
        );
    }

    public function test_it_rejects_an_external_dtd_over_network(): void
    {
        $this->expectException(DoctypeNotAllowedException::class);

        Document::fromXmlString(
            '<?xml version="1.0"?><!DOCTYPE r SYSTEM "http://127.0.0.1:1/x.dtd"><r/>',
            disallow_doctype()
        );
    }

    public function test_it_allows_a_document_without_a_doctype(): void
    {
        $document = Document::fromXmlString(
            '<?xml version="1.0"?><r>ok</r>',
            disallow_doctype()
        );

        static::assertSame('<r>ok</r>', xml_string()($document->locateDocumentElement()));
    }
}
