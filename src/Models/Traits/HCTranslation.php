<?php
/**
 * Created by PhpStorm.
 * User: jevge
 * Date: 2018-01-20
 * Time: 23:23
 */

namespace HoneyComb\Core\Models\Traits;


trait HCTranslation
{
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
