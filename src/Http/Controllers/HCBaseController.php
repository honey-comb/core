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

namespace HoneyComb\Core\Http\Controllers;

use HoneyComb\Starter\Contracts\HCDataTableContract;
use HoneyComb\Starter\Contracts\HCDataTableHeaderContract;
use HoneyComb\Starter\Contracts\HCViewContract;
use HoneyComb\Starter\Views\HCDataTableHeader;
use HoneyComb\Starter\Views\HCView;
use HoneyComb\Starter\Views\HCDataTable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * Class HCBaseController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Getting allowed actions for admin view
     *
     * @param string $prefix
     * @param array $except
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getUserActions(string $prefix, array $except = []): array
    {
        $actions = [];

        if (!in_array('_search', $except)) {
            $actions[] = 'search';
        }

        if (!in_array('_create', $except) && auth()->user()->can($prefix . '_create')) {
            $actions[] = 'new';
        }

        if (!in_array('_update', $except) && auth()->user()->can($prefix . '_update')) {
            $actions[] = 'update';
        }

        if (!in_array('_delete', $except) && auth()->user()->can($prefix . '_delete')) {
            $actions[] = 'delete';
        }

        if (!in_array('_restore', $except) && auth()->user()->can($prefix . '_restore')) {
            $actions[] = 'restore';
        }

        if (!in_array('_force_delete', $except) && auth()->user()->can($prefix . '_force_delete')) {
            $actions[] = 'forceDelete';
        }

        if (!in_array('_merge', $except) && auth()->user()->can($prefix . '_merge')) {
            $actions[] = 'merge';
        }

        if (!in_array('_clone', $except) && auth()->user()->can($prefix . '_clone')) {
            $actions[] = 'clone';
        }

        return $actions;
    }

    /**
     * @param string $key
     * @param string|null $label
     * @return HCViewContract
     */
    protected function makeView(string $key, string $label = null): HCViewContract
    {
        return new HCView($key, $label);
    }

    /**
     * @param string $key
     * @param string $source
     * @return HCDataTableContract
     */
    protected function makeDataTable(string $key, string $source): HCDataTableContract
    {
        return new HCDataTable($key, $source);
    }
}
