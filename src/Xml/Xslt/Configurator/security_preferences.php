<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt\Configurator;

use Closure;
use XSLTProcessor;

/**
 * @see https://www.php.net/manual/en/xsltprocessor.setsecurityprefs.php
 *
 * @param int-mask<
 *     \XSL_SECPREF_NONE,
 *     \XSL_SECPREF_READ_FILE,
 *     \XSL_SECPREF_WRITE_FILE,
 *     \XSL_SECPREF_CREATE_DIRECTORY,
 *     \XSL_SECPREF_READ_NETWORK,
 *     \XSL_SECPREF_WRITE_NETWORK,
 *     \XSL_SECPREF_DEFAULT
 * > $preferences
 *
 * @return Closure(XSLTProcessor): XSLTProcessor
 */
function security_preferences(int $preferences): Closure
{
    return static function (XSLTProcessor $processor) use ($preferences) : XSLTProcessor {
        $processor->setSecurityPrefs($preferences);

        return $processor;
    };
}
