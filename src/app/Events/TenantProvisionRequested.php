<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenantProvisionRequested
{
    use Dispatchable, SerializesModels;

    /**
     * @param array<string,mixed> $data
     * @param array<string,string|null> $tmpFiles  absolute temp path from UploadedFile::getRealPath()
     */
    public function __construct(
        public array $data,
        public array $tmpFiles,
        public string $rawPassword,
    ) {
    }
}
