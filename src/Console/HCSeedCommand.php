<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Console;

use Illuminate\Console\Command;
use HoneyComb\Core\Helpers\HCConfigParseHelper;

class HCSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds honeycomb packages';

    /**
     * @var HCConfigParseHelper
     */
    private $helper;

    /**
     * HCSeedCommand constructor.
     * @param HCConfigParseHelper $helper
     */
    public function __construct(HCConfigParseHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle()
    {
        $filePaths = $this->helper->getConfigFilesSorted();

        $seeds = [];

        foreach ($filePaths as $filePath) {

            $file = json_decode(file_get_contents($filePath), true);

            if (isset($file['seeder'])) {
                $seeds[] = $file['seeder'];
            }
        }

        foreach ($seeds as $class) {
            if (class_exists($class)) {
                if (app()->environment() == 'production') {
                    $this->call('db:seed', ['--class' => $class, '--force' => true]);
                } else {
                    $this->call('db:seed', ['--class' => $class]);
                }
            }
        }

        if (app()->environment() == 'production') {
            $this->call('db:seed', ['--force' => true]);
        } else {
            $this->call('db:seed');
        }
    }
}
