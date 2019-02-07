<?php
/**
 * @copyright 2019 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

declare(strict_types = 1);

namespace HoneyComb\Core\Console;

use HoneyComb\Starter\Helpers\HCConfigParseHelper;
use Illuminate\Console\Command;

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
