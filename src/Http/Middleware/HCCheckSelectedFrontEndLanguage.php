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
use HoneyComb\Core\Repositories\HCLanguageRepository;
use Illuminate\Http\Request;

/**
 * Class HCCheckSelectedFrontEndLanguage
 * @package HoneyComb\Core\Http\Middleware
 */
class HCCheckSelectedFrontEndLanguage
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
        $languageCode = $request->segment(1);

        $appLocale = config()->get('app.locale');

        if (is_null($languageCode)) {
            // get cookie language or get default app locale
            $locale = $request->cookie('lang-front-end', $appLocale);

            return redirect()->to(
                $this->getRedirectUrl($request, $locale)
            );
        }

        // get language from cookie
        $cookieLocale = $request->cookie('lang-front-end');

        $enabled = $this->languageRepository->getFrontEndActiveLanguages();

        if ($enabled->contains('iso_639_1', $languageCode)) {
            // language from url is available to access
            app()->setLocale($languageCode);

            // save locale to cookie
            if ($cookieLocale != $languageCode) {
                cookie()->queue(
                    cookie()->make('lang-front-end', $languageCode, 60 * 24 * 30)
                );
            }

            return $next($request);
        }

        // language from url is not available to access

        if ($enabled->contains('iso_639_1', $cookieLocale)) {
            // language from cookie is available to access
            return redirect()->to(
                $this->getRedirectUrl($request, $cookieLocale)
            );
        }

        // if cookie locale and language code from url is not valid than redirect to default locale
        // and also remove cookie

        cookie()->queue(
            cookie()->make('lang-front-end', '', -1)
        );

        return redirect()->to(
            $this->getRedirectUrl($request, $appLocale)
        );
    }

    /**
     * Get changed url
     *
     * @param Request $request
     * @param string $appLocale
     * @return string
     */
    private function getRedirectUrl(Request $request, string $appLocale): string
    {
        $segments = $request->segments();
        $segments = str_replace(["\r", "\n"], '', $segments);
        $segments = array_prepend($segments, $appLocale);

        // domain
        $url = $request->root();
        // segments
        $url .= DIRECTORY_SEPARATOR . implode('/', $segments);
        // query
        if ($request->getQueryString()) {
            $url .= ('?' . $request->getQueryString());
        }

        return $url;
    }
}
