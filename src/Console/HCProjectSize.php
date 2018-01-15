<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Console;

use Illuminate\Console\Command;
use HoneyComb\Core\Helpers\HCConfigParseHelper;
use Illuminate\Support\Facades\DB;
use mysqli;

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
        $this->calculateDisk();
        $this->calculateDB();
    }

    private function calculateDisk()
    {
        $time = microtime(true);

        cache()->put('project-size-files', formatSize(folderSize(app_path('/../'))), 1500);

        $this->info(cache()->get('project-size-files'));
        $this->info(microtime(true) - $time);
    }

    private function calculateDB()
    {
        $mysqli = new mysqli(env("DB_HOST"), env("DB_USERNAME"), env("DB_PASSWORD"), env("DB_DATABASE"));
        $query = 'SELECT TABLE_NAME AS `Table`, (DATA_LENGTH + INDEX_LENGTH)
                  AS `size`
                  FROM information_schema.TABLES
                  WHERE TABLE_SCHEMA = "' . env('DB_DATABASE') . '"
                  ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC;';

        $size = 0;

        /* If we have to retrieve large amount of data we use MYSQLI_USE_RESULT */
        if ($result = $mysqli->query($query)) {

            foreach ($result as $item) {
                $size += $item['size'];
            };

            $result->close();
        }

        cache()->put('project-size-db', formatSize($size), 1500);
    }
}
