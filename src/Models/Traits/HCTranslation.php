<?php
/**
 * @copyright 2018 interactivesolutions
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
 * E-mail: info@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Core\Models\Traits;


/**
 * Trait HCTranslation
 * @package HoneyComb\Core\Models\Traits
 */
trait HCTranslation
{
    /**
     * @var
     */
    private $translationClass;

    /**
     * Translations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        $this->translationClass = get_class($this) . 'Translations';

        return $this->hasMany($this->translationClass, 'record_id', 'id');
    }

    /**
     * Single translation only
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function translation()
    {
        if (is_null($this->translationClass)) {
            $this->translationClass = get_class($this) . 'Translations';
        }

        return $this->hasOne($this->translationClass, 'record_id', 'id')->where('language_code', app()->getLocale());
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
            'language_code' => array_get($data, 'language_code'),
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
    public function updateTranslations(array $data = [])
    {
        foreach ($data as $translationsData) {
            $this->updateTranslation($translationsData);
        }
    }
}
