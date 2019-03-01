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

use Carbon\Carbon;
use HoneyComb\Starter\Helpers\HCConfigParseHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class HCGenerateAdminMenuCommand
 * @package HoneyComb\Core\Console
 */
class HCGenerateAdminMenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:admin-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through honeycomb related packages and get all menu items';

    /**
     * Menu list holder
     *
     * @var array
     */
    private $adminMenuHolder = [];

    /**
     * @var HCConfigParseHelper
     */
    private $helper;

    /**
     * HCGenerateAdminMenuCommand constructor.
     * @param HCConfigParseHelper $helper
     */
    public function __construct(HCConfigParseHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->comment('Scanning menu items..');
        $this->generateMenu();
        $this->info('Menu items: ' . count($this->adminMenuHolder));
        $this->comment('-');
    }

    /**
     * Get admin menu
     */
    private function generateMenu(): void
    {
        $filePaths = $this->helper->getConfigFilesSorted();

        foreach ($filePaths as $filePath) {
            $file = json_decode(file_get_contents($filePath), true);

            if (isset($file['adminMenu'])) {
                if (Str::contains($filePath, 'app/hc-config.json')) {
                    // merge project level menu items or override package
                    $projectMenu = $this->overridePackageItems($file);

                    $this->adminMenuHolder = array_merge($this->adminMenuHolder, $projectMenu);
                } else {
                    $this->adminMenuHolder = array_merge($this->adminMenuHolder, $file['adminMenu']);
                }
            }
        }

        cache()->forget(config('hc.admin_menu_cache_key'));
        cache()->put(config('hc.admin_menu_cache_key'), $this->adminMenuHolder, Carbon::now()->addYear(2));
    }

    /**
     * @param array $file
     * @return array
     */
    private function overridePackageItems(array $file): array
    {
        $projectItems = $file['adminMenu'];
        $toRemove = [];

        foreach ($this->adminMenuHolder as $key => $menuItem) {
            // find existing project and package menu item by route
            $found = Arr::first($projectItems, function (array $item) use ($menuItem) {
                return $menuItem['route'] === $item['route'];
            });

            if ($found) {
                // replace existing
                $this->adminMenuHolder[$key] = $found;
                $toRemove[] = $found['route'];
            }
        }

        if ($toRemove) {
            // remove from project menu items
            foreach ($projectItems as $key => $projectItem) {
                if (in_array($projectItem['route'], $toRemove)) {
                    Arr::forget($projectItems, $key);
                }
            }
        }

        return $projectItems;
    }
}
