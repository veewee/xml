<?php

declare(strict_types=1);

namespace VeeWee\Xml\Writer\Configurator;

use Closure;
use XMLWriter;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return Closure(XMLWriter): XMLWriter
 */
function indentation(string $indentation): Closure
{
    return static function (XMLWriter $writer) use ($indentation): XMLWriter {
        disallow_libxml_false_returns(
            $writer->setIndent(true),
            'Unable to enable writer indentation.'
        );

        disallow_libxml_false_returns(
            $writer->setIndentString($indentation),
            'Unable to register indentation string.'
        );

        return $writer;
    };
}
