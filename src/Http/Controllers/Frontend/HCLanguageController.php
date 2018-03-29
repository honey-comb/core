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

namespace HoneyComb\Core\Http\Controllers\Frontend;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Repositories\HCLanguageRepository;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;

/**
 * Class HCLanguageController
 * @package HoneyComb\Core\Http\Controllers\Frontend
 */
class HCLanguageController extends HCBaseController
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var HCFrontendResponse
     */
    protected $response;

    /**
     * @var HCLanguageRepository
     */
    protected $languageRepository;

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
     * Change language
     *
     * @param Request $request
     * @param string $location
     * @param string $lang
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function change(Request $request, string $location, string $lang)
    {
        switch ($location) {
            case 'front-end' :
                if (!$this->languageRepository->isAvailableForChange($lang, 'front_end')) {
                    if ($request->wantsJson()) {
                        return $this->response->error(trans('HCCore::core.language_not_found'));
                    }

                    return redirect()->back()->withErrors(trans('HCCore::core.language_not_found'));
                }

                session()->put('front-end', $lang);
                session()->put('content', $lang);

                break;

            case 'back-end' :
                if (!$this->languageRepository->isAvailableForChange($lang, 'back_end')) {
                    if ($request->wantsJson()) {
                        return $this->response->error(trans('HCCore::core.language_not_found'));
                    }

                    return redirect()->back()->withErrors(trans('HCCore::core.language_not_found'));
                }

                session()->put('back-end', $lang);

                break;

            case 'content' :
                if (!$this->languageRepository->isAvailableForChange($lang, 'content')) {
                    if ($request->wantsJson()) {
                        return $this->response->error(trans('HCCore::core.language_not_found'));
                    }

                    return redirect()->back()->withErrors(trans('HCCore::core.language_not_found'));
                }

                session()->put('content', $lang);
                break;

            default:
                return $this->response->error(trans('HCCore::core.language_not_found'));
        }

        if ($request->wantsJson()) {
            return $this->response->success('Language changed');
        }

        return redirect()->back();
    }
}
