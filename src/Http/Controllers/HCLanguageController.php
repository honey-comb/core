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

use HoneyComb\Starter\Helpers\HCResponse;
use HoneyComb\Starter\Http\Requests\HCLanguageRequest;
use HoneyComb\Starter\Services\HCLanguageService;
use HoneyComb\Starter\Views\HCDataList;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class HCLanguageController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCLanguageController extends HCBaseController
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var HCResponse
     */
    protected $response;

    /**
     * @var HCLanguageService
     */
    protected $service;

    /**
     * HCUsersController constructor.
     * @param Connection $connection
     * @param HCResponse $response
     * @param HCLanguageService $service
     */
    public function __construct(
        Connection $connection,
        HCResponse $response,
        HCLanguageService $service
    ) {

        $this->service = $service;
        $this->connection = $connection;
        $this->response = $response;
    }

    /**
     * Admin panel page config
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $config = $this->makeView('language-view', trans('HCCore::languages.title.list'))
            ->addForm('add-new', 'language')
            ->addDataList($this->getDataList())
            ->addActions(['search'])
            ->toArray();

        return $this->response->success('OK', $config);
    }

    /**
     * Creating data list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getListPaginate(Request $request): JsonResponse
    {
        return $this->response->success('OK', $this->service->getRepository()->getListPaginate($request));
    }

    /**
     * Updates existing specific items based on ID
     *
     * @param HCLanguageRequest $request
     * @param string $languageId
     * @return JsonResponse
     * @throws \Exception
     */
    public function patch(HCLanguageRequest $request, string $languageId): JsonResponse
    {
        $this->service->update($request, $languageId);

        return $this->response->success(trans('HCCore::languages.message.lang_updated'));
    }

    /**
     * Creating data list
     * @param HCLanguageRequest $request
     * @return JsonResponse
     */
    public function getOptions(HCLanguageRequest $request): JsonResponse
    {
        return $this->response->success('OK', $this->service->getRepository()->getOptions($request));
    }

    /**
     * Get admin page table columns settings
     *
     * @return HCDataList
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getDataList(): HCDataList
    {
        return $this->makeDataList('languages', route('v1.api.languages.list'))
            ->headerAddText('language_family', trans('HCCore::languages.label.language_family'))
            ->headerAddText('language', trans('HCCore::languages.label.language'))
            ->headerAddText('native_name', trans('HCCore::languages.label.native_name'))
            ->headerAddText('iso_639_1', trans('HCCore::languages.label.iso_639_1'))
            ->headerAddText('iso_639_2', trans('HCCore::languages.label.iso_639_2'))
            ->headerAddCheckbox('is_content', trans('HCCore::languages.label.content'))
            ->headerAddCheckbox('is_interface', trans('HCCore::languages.label.interface'));
    }
}
