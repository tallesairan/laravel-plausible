<?php

namespace Airan\Plausible\Commands;

use Illuminate\Console\Command;

class PlausibleCommand extends Command
{
    public $signature = 'app:laravel-plausible';

    public $description = 'Laravel plausible';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
