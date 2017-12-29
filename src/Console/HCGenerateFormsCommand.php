<?php
/**
 * @copyright 2017 interactivesolutions
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
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Core\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use HoneyComb\Core\Helpers\HCConfigParseHelper;

/**
 * Class HCGenerateFormsCommand
 * @package HoneyComb\Core\Console
 */
class HCGenerateFormsCommand extends Command
{
    /**
     * @var HCConfigParseHelper
     */
    private $helper;

    /**
     * HCGenerateFormsCommand constructor.
     * @param HCConfigParseHelper $helper
     */
    public function __construct(HCConfigParseHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hc:forms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go through honeycomb related packages config files and get all form settings';


    /**
     * Get all honeycomb form config files and add it to cache
     */
    public function handle(): void
    {
        $this->comment('Scanning form items..');

        $filePaths = $this->helper->getConfigFilesSorted();

        $formHolder = [];

        foreach ($filePaths as $filePath) {

            $file = json_decode(file_get_contents($filePath), true);

            if (isset($file['formData'])) {
                $formHolder = array_merge($formHolder, $file['formData']);
            }
        }

        cache()->forget('hc-forms');
        cache()->put('hc-forms', $formHolder, Carbon::now()->addMonth());

        $this->info('registered forms: ' . count($formHolder));
        $this->comment('-');
    }
}
