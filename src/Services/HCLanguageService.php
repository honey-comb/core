<?php
/**
 * @copyright 2018 innovationbase
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

namespace HoneyComb\Core\Services;

use HoneyComb\Core\Http\Requests\Admin\HCLanguageRequest;
use HoneyComb\Core\Repositories\HCLanguageRepository;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;

/**
 * Class HCUserService
 * @package HoneyComb\Core\Services
 */
class HCLanguageService
{
    use HCQueryBuilderTrait;

    /**
     * @var HCLanguageRepository
     */
    protected $repository;

    /**
     * HCUserService constructor.
     * @param HCLanguageRepository $repository
     */
    public function __construct(HCLanguageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCLanguageRepository
     */
    public function getRepository(): HCLanguageRepository
    {
        return $this->repository;
    }

    /**
     * @param HCLanguageRequest $request
     * @param string $languageId
     * @throws \Exception
     */
    public function update(HCLanguageRequest $request, string $languageId)
    {
        cache()->forget($this->getRepository()->getFeCacheKey());

        $this->getRepository()->update($request->getStrictUpdateValues(), $languageId);
    }
}
