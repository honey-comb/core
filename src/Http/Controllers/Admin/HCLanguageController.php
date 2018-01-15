<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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

namespace HoneyComb\Core\Http\Controllers\Admin;

use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Core\Http\Requests\HCLanguageRequest;
use HoneyComb\Core\Repositories\HCLanguageRepository;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use HoneyComb\Core\Helpers\HCFrontendResponse;
use HoneyComb\Core\Http\Controllers\HCBaseController;

class HCLanguageController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var HCFrontendResponse
     */
    private $response;

    /**
     * @var HCLanguageRepository
     */
    private $languageRepository;

    /**
     * HCUsersController constructor.
     * @param Connection $connection
     * @param HCLanguageRepository $languageRepository
     * @param HCFrontendResponse $response
     */
    public function __construct(
        Connection $connection,
        HCLanguageRepository $languageRepository,
        HCFrontendResponse $response
    ) {
        $this->connection = $connection;
        $this->response = $response;
        $this->languageRepository = $languageRepository;
    }

    /**
     * Admin panel page view
     *
     * @return View
     */
    public function index(): View
    {
        $config = [
            'title' => trans('HCCore::language.page_title'),
            'url' => route('admin.api.language'),
            'form' => route('admin.api.form-manager', ['language']),
            'headers' => $this->getTableColumns(),
            'actions' => [],
        ];

        return view('HCCore::admin.service.index', ['config' => $config]);
    }

    /**
     * Get admin page table columns settings
     *
     * @return array
     */
    public function getTableColumns(): array
    {
        $columns = [
            'language_family' => $this->headerText(trans('HCCore::language.language_family')),
            'language' => $this->headerText(trans('HCCore::language.language')),
            'native_name' => $this->headerText(trans('HCCore::language.native_name')),
            'iso_639_1' => $this->headerText(trans('HCCore::language.iso_639_1')),
            'iso_639_2' => $this->headerText(trans('HCCore::language.iso_639_2')),
            'front_end' => $this->headerCheckBox(trans('HCCore::language.front_end')),
            'back_end' => $this->headerCheckBox(trans('HCCore::language.back_end')),
            'content' => $this->headerCheckBox(trans('HCCore::language.content')),
        ];

        return $columns;
    }

    /**
     * Creating data list
     * @param Request $request
     * @return JsonResponse
     */
    public function getListPaginate(Request $request): JsonResponse
    {
        return response()->json(
            $this->languageRepository->getListPaginate($request)
        );
    }

    /**
     * Updates existing specific items based on ID
     *
     * @param HCLanguageRequest $request
     * @param string $languageId
     * @return mixed
     */
    public function updateStrict(HCLanguageRequest $request, string $languageId)
    {
        $this->languageRepository->update($request->getStrictUpdateValues(), $languageId);

        return $this->response->success('Updated');
    }
}
