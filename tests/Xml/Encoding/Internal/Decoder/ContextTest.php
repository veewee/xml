<?php

declare(strict_types=1);

namespace VeeWee\Tests\Xml\Encoding\Internal\Decoder;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Encoding\Internal\Decoder\Context;
use VeeWee\Xml\Xmlns\Xmlns;
use function Psl\Vec\keys;

final class ContextTest extends TestCase
{
    public function test_it_can_build_a_context_from_xml(): void
    {
        $doc = Document::fromXmlString('<root xmlns="http://hello" xmlns:test="http://world" />');
        $context = Context::fromDocument($doc);

        static::assertSame($doc, $context->document());
        static::assertSame(['test'], keys($context->knownNamespaces()));
        static::assertTrue($context->knownNamespaces()['test']->matches(Xmlns::load("http://world")));
    }
}
