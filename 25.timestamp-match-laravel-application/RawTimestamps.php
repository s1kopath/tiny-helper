<?php

namespace App\Traits;

use DateTimeInterface;

trait RawTimestamps
{
    /**
     * Prevent Laravel from trying to serialize DateTime objects.
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
