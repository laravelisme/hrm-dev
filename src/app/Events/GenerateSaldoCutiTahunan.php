<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerateSaldoCutiTahunan
{
    use Dispatchable, SerializesModels;

    public int $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }
}
