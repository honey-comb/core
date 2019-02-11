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

namespace HoneyComb\Core\Http\Middleware;

use Closure;
use HoneyComb\Starter\Repositories\HCLanguageRepository;
use Illuminate\Http\Request;

/**
 * Class HCCheckSelectedLanguage
 * @package HoneyComb\Core\Http\Middleware
 */
class HCCheckSelectedLanguage
{
    /**
     * @var HCLanguageRepository
     */
    private $languageRepository;

    /**
     * HCCheckSelectedAdminLanguage constructor.
     * @param HCLanguageRepository $languageRepository
     */
    public function __construct(HCLanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->has(config('hc.header_language_key'))) {
            $enabled = $this->languageRepository->getInterfaceActiveLanguages();

            $locale = $request->headers->get(config('hc.header_language_key'));

            if (!$enabled->contains('iso_639_1', $locale)) {
                $locale = config('app.locale');
            }

            app()->setLocale($locale);
        }

        return $next($request);
    }
}
