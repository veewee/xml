<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use VeeWee\Xml\Reader\Node\NodeSequence;
use Webmozart\Assert\Assert;
use XMLReader;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

function current_node_has_attribute(string $attribute, string $value): bool
{
    return static function (NodeSequence $sequence): bool {
        $sequence->matches()



    }



    return static function () use ($file): XMLReader {
        Assert::fileExists($file);

        return disallow_libxml_false_returns(
            XMLReader::open($file),
            'Could not open the provided XML file!'
        );
    };
}
