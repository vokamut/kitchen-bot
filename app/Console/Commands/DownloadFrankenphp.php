<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Octane\Commands\Concerns\InstallsFrankenPhpDependencies;

final class DownloadFrankenphp extends Command
{
    use InstallsFrankenPhpDependencies;

    protected $signature = 'download-frankenphp';

    protected $description = 'Скачивает frankenphp';

    public function handle(): void
    {
        $this->downloadFrankenPhpBinary();
    }
}
