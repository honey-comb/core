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
use HoneyComb\Starter\Enum\BoolEnum;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class HCLanguageRepository
 * @package HoneyComb\Core\Repositories
 */
class HCLanguageRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @var string
     */
    protected $feCacheKey = '_hc_fe_languages';

    /**
     * @return string
     */
    public function getFeCacheKey(): string
    {
        return $this->feCacheKey;
    }

    /**
     * @return string
     */
    public function model(): string
    {
        return HCLanguage::class;
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
     * Get all available frontend languages
     *
     * @return Collection
     */
    public function getFrontEndActiveLanguages(): Collection
    {
        return cache()->remember($this->getFeCacheKey(), 60 * 24 * 7, function () {
            return $this->makeQuery()->where('front_end', BoolEnum::yes()->id())->get();
        });
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
