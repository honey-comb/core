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

namespace HoneyComb\Core\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

/**
 * Trait HCTranslation
 * @package HoneyComb\Core\Models\Traits
 */
trait HCTranslation
{
    /**
     * Translations
     *
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany($this->getTranslationClass(), 'record_id', 'id');
    }

    /**
     * Single translation only
     *
     * @return HasOne
     */
    public function translation(): HasOne
    {
        return $this->hasOne($this->getTranslationClass(), 'record_id', 'id')
            ->where('language_code', app()->getLocale());
    }

    /**
     * Update translations
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateTranslation(array $data)
    {
        $translation = $this->translations()->where([
            'record_id' => $this->id,
            'language_code' => Arr::get($data, 'language_code'),
        ])->first();

        if (is_null($translation)) {
            $translation = $this->translations()->create($data);
        } else {
            $translation->update($data);
        }

        return $translation;
    }

    /**
     * Update multiple translations at once
     *
     * @param array $data
     */
    public function updateTranslations(array $data = []): void
    {
        foreach ($data as $translationsData) {
            $this->updateTranslation($translationsData);
        }
    }

    /**
     * @return string
     */
    public function getTranslationClass()
    {
        return get_class($this) . 'Translation';
    }
}
