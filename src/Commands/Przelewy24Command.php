<?php

namespace Kuzry\Przelewy24\Commands;

use Illuminate\Console\Command;

class Przelewy24Command extends Command
{
    public $signature = 'laravel-przelewy24';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
