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

namespace HoneyComb\Core\Repositories;

use HoneyComb\Core\Http\Requests\Admin\HCLanguageRequest;
use HoneyComb\Core\Models\HCLanguage;
use HoneyComb\Starter\Enum\BoolEnum;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;
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
    protected $interfaceCacheKey = '_hc_interface_languages';

    /**
     * @return string
     */
    public function model(): string
    {
        return HCLanguage::class;
    }

    /**
     * @return string
     */
    public function getInterfaceCacheKey(): string
    {
        return $this->interfaceCacheKey;
    }

    /**
     * Get all available interface languages
     *
     * @return Collection
     * @throws \Exception
     */
    public function getInterfaceActiveLanguages(): Collection
    {
        return cache()->remember($this->getInterfaceCacheKey(), 60 * 24 * 7, function () {
            return $this->makeQuery()->where('interface', BoolEnum::yes()->id())->get();
        });
    }

    /**
     * @param HCLanguageRequest $request
     * @return \Illuminate\Support\Collection|static
     */
    public function getOptions(HCLanguageRequest $request)
    {
        return $this->createBuilderQuery($request)->get()->map(function ($record) {
            return [
                'id' => $record->id,
                'language' => $record->language,
            ];
        });
    }
}
