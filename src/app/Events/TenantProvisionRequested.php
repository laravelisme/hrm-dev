<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenantProvisionRequested
{
    use Dispatchable, SerializesModels;

    /**
     * @param array<string,mixed> $data
     * @param array<string,string|null> $uploadedPaths  stored paths on configured disk (e.g. public)
     */
    public function __construct(
        public array $data,
        public array $uploadedPaths,
        public string $rawPassword,
    ) {
    }
}
