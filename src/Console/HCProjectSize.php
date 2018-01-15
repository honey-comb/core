<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Console;

use Illuminate\Console\Command;

/**
 * Class HCProjectSize
 * @package HoneyComb\Core\Console
 */
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
    public function handle(): void
    {
        $this->calculateDisk();
        $this->calculateDB();
    }

    /**
     * @throws \Exception
     */
    private function calculateDisk(): void
    {
        //adding node_modules and vendor directories sizes
        cache()->put('project-size-files', formatSize((200 + 36) * 1024 * 1024 + folderSize(app_path('../'), ['../node_modules', '../vendor'])), 1500);

        $this->info(cache()->get('project-size-files'));
    }

    /**
     * @throws \Exception
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    private function calculateDB(): void
    {
        $result = \DB::select('SELECT TABLE_NAME AS `Table`, (DATA_LENGTH + INDEX_LENGTH)
                  AS `size`
                  FROM information_schema.TABLES
                  WHERE TABLE_SCHEMA = "' . config('database.connections.mysql.database') . '"
                  ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC;');

        $size = 0;

        if ($result) {
            foreach ($result as $item) {
                $size += $item->size;
            };
        }

        cache()->put('project-size-db', formatSize($size), 1500);

        $this->info(cache()->get('project-size-db'));
    }
}
