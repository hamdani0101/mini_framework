<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Base model: plain data representation, no SQL here.
 */
abstract class Model
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
