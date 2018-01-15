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

namespace HoneyComb\Core\Repositories;

use HoneyComb\Core\Models\HCLanguage;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class HCLanguageRepository
 * @package HoneyComb\Core\Repositories
 */
class HCLanguageRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCLanguage::class;
    }

    /**
     * @param Request $request
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function getListPaginate(
        Request $request,
        int $perPage = self::DEFAULT_PER_PAGE,
        array $columns = ['*']
    ): LengthAwarePaginator {

        if ($request->has('per_page')) {
            $perPage = $request->get('per_page');
        }

        return $this->createBuilderQuery($request)->paginate($perPage, $columns)->appends($request->all());
    }

    /**
     * Get all available admin languages
     *
     * @return Collection
     */
    public function getAdminActiveLanguages(): Collection
    {
        return $this->makeQuery()->where('back_end', '1')->get();
    }

    /**
     * Get all available admin languages
     *
     * @return Collection
     */
    public function getFrontEndActiveLanguages(): Collection
    {
        return $this->makeQuery()->where('back_end', '1')->get();
    }

    /**
     * Check if given language is available to access
     *
     * @param string $lang
     * @param string $location
     * @return bool
     */
    public function isAvailableForChange(string $lang, string $location): bool
    {
        return $this->makeQuery()->where(['iso_639_1' => $lang, $location => 1])->exists();
    }
}
