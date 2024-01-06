<?php
declare(strict_types=1);

namespace VeeWee\Xml\Reader;

final class Signal
{
    private bool $stopRequested = false;

    public function stop(): void
    {
        $this->stopRequested = true;
    }

    public function stopRequested(): bool
    {
        return $this->stopRequested;
    }
}
