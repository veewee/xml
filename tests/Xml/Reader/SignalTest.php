<?php
declare(strict_types=1);

namespace VeeWee\Tests\Xml\Reader;

use PHPUnit\Framework\TestCase;
use VeeWee\Xml\Reader\Signal;

final class SignalTest extends TestCase
{
    
    public function test_it_can_signal_a_stop_command(): void
    {
        $signal = new Signal();
        static::assertFalse($signal->stopRequested());

        $signal->stop();
        static::assertTrue($signal->stopRequested());
    }
}
