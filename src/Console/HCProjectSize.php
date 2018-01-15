<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Console;

use Illuminate\Console\Command;
use HoneyComb\Core\Helpers\HCConfigParseHelper;

class HCProjectSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:project-size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates project size';


    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $time = microtime(true);

        cache()->put('project-size', formatSize(folderSize(app_path('/../'))), 1500);

        $this->info(cache()->get('project-size'));
        $this->info(microtime(true) - $time);
    }
}
