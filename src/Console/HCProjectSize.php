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
        $this->calculateDB();
    }

    /**
     * @throws \Exception
     */
    private function calculateDisk(): void
    {
        //adding node_modules and vendor directories sizes
        cache()->put('project-size-files',
            formatSize((200 + 36) * 1024 * 1024 + folderSize(app_path('../'), ['../node_modules', '../vendor'])), 1500);

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
