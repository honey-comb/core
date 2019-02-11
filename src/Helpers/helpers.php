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

use HoneyComb\Core\Models\HCLanguage;
use Illuminate\Database\Eloquent\Collection;

if (!function_exists('get_translation_name')) {
    /**
     * Get translation name from
     *
     * @param string $key
     * @param string $lang
     * @param array $data
     * @param null $customNotFoundText
     * @return mixed
     */
    function getTranslationName(string $key, string $lang, array $data, $customNotFoundText = null)
    {
        if (is_array($data)) {
            $data = collect($data);
        }

        $item = $data->where('language_code', $lang)->first();

        if (is_null($item)) {
            $name = array_get($data, '0.' . $key);
        } else {
            $name = array_get($item, $key);
        }

        if (is_null($name)) {
            if (is_null($customNotFoundText)) {
                $name = trans('HCStarter::core.no_translation');
            } else {
                $name = $customNotFoundText;
            }
        }

        return $name;
    }
}


if (!function_exists('fontAwesomeIcon')) {
    /**
     * creating a html tag for font awesome icon
     *
     * @param string $icon
     * @param string $prefix
     * @param string $class
     * @return string
     */
    function fontAwesomeIcon(string $icon, string $prefix = "", string $class = "")
    {
        return "<div class=\"fa-icon $class\" data-icon=\"$icon\" data-prefix=\"$prefix\"></div>";
    }
}


if (!function_exists('folderSize')) {

    /**
     * Scanning folder size
     *
     * @param string $dir
     * @param array $ignore
     * @return int
     */
    function folderSize(string $dir, array $ignore): int
    {
        foreach ($ignore as $key => $value) {
            if (strpos($dir, $value) !== false) {
                return 0;
            }
        }

        $size = 0;
        foreach (glob(rtrim($dir, '/') . '/*') as $each) {
            $size += is_file($each) ? filesize($each) : folderSize($each, $ignore);
        }

        return $size;
    }
}


if (!function_exists('formatSize')) {
    function formatSize(int $bytes): string
    {
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;
        if (($bytes >= 0) && ($bytes < $kb)) {
            return $bytes . ' B';
        } elseif (($bytes >= $kb) && ($bytes < $mb)) {
            return ceil($bytes / $kb) . ' KB';
        } elseif (($bytes >= $mb) && ($bytes < $gb)) {
            return ceil($bytes / $mb) . ' MB';
        } elseif (($bytes >= $gb) && ($bytes < $tb)) {
            return ceil($bytes / $gb) . ' GB';
        } elseif ($bytes >= $tb) {
            return ceil($bytes / $tb) . ' TB';
        } else {
            return $bytes . ' B';
        }
    }
}


if (!function_exists('getProjectFileSize')) {
    /**
     *
     * Getting project size
     *
     */
    function getProjectFileSize()
    {
        if (!cache()->has('project-size-files')) {
            \Illuminate\Support\Facades\Artisan::call('hc:project-size');
        }

        return cache()->get('project-size-files');
    }
}


if (!function_exists('getProjectDbSize')) {
    /**
     *
     * Getting project size
     *
     */
    function getProjectDbSize()
    {
        if (!cache()->has('project-size-db')) {
            \Illuminate\Support\Facades\Artisan::call('hc:project-size');
        }

        return cache()->get('project-size-db');
    }
}


if (!function_exists('addAllOptionToDropDownList')) {

    /**
     * Adding All options to Drop down list
     *
     * @param array $fieldData
     * @return array
     */
    function addAllOptionToDropDownList(array $fieldData)
    {
        array_unshift(
            $fieldData['options'],
            ['id' => '', $fieldData['showNodes'][0] => trans('HCStarter::core.all')]
        );

        return $fieldData;
    }
}


if (!function_exists('createTranslationKey')) {
    //TODO move to Translations package
    //TODO improve removal of ,/'?[][\ and etc...
    /**
     * From given string creates a translations string
     *
     * @param string $string
     * @return mixed
     */
    function createTranslationKey(string $string)
    {
        return str_replace(' ', '_', strtolower($string));
    }
}


if (!function_exists('checkActiveMenuItems')) {

    /**
     * Check if menu item has active sub menu element
     *
     * @param array $item
     * @param string $routeName
     * @return bool
     */
    function checkActiveMenuItems(array $item, string $routeName): bool
    {
        if ($item['route'] == $routeName) {
            return true;
        }

        if (array_key_exists('children', $item)) {
            foreach ($item['children'] as $child) {
                $found = checkActiveMenuItems($child, $routeName);

                if ($found) {
                    return true;
                }
            }
        }

        return false;
    }
}


if (!function_exists('formManagerSeo')) {

    /**
     * Adding seo fields (title, description, keywords
     * used by Form-Managers
     *
     * @param array $list
     * @param bool $multiLanguage
     */
    function formManagerSeo(array &$list, bool $multiLanguage = true): void
    {
        $list['structure'] = array_merge(
            $list['structure'],
            [
                [
                    'type' => 'singleLine',
                    'fieldId' => 'translations.seo_title',
                    'label' => trans('HCStarter::core.seo_title'),
                    'tabID' => trans('HCStarter::core.seo'),
                    'multiLanguage' => $multiLanguage,
                ],
                [
                    'type' => 'textArea',
                    'fieldId' => 'translations.seo_description',
                    'label' => trans('HCStarter::core.seo_description'),
                    'tabID' => trans('HCStarter::core.seo'),
                    'multiLanguage' => $multiLanguage,
                    'rows' => 5,
                ],
                [
                    'type' => 'singleLine',
                    'fieldId' => 'translations.seo_keywords',
                    'label' => trans('HCStarter::core.seo_keywords'),
                    'tabID' => trans('HCStarter::core.seo'),
                    'multiLanguage' => $multiLanguage,
                ],
            ]
        );
    }
}


if (!function_exists('removeRecordsWithNoTranslation')) {

    /**
     * Removing records from array with no translation
     * used by Front-End
     *
     * @param array $list
     * @return array
     */
    function removeRecordsWithNoTranslation(array $list): array
    {
        $contentList = [];

        foreach ($list as $item) {
            if ($item['translation'] !== null) {
                array_push($contentList, $item);
            }
        }

        return $contentList;
    }
}

if (!function_exists('getHCLanguagesOptions')) {
    /**
     * Retrieving languages
     *
     * @param null|string $type
     * @param array $columns
     * @return \Illuminate\Support\Collection
     * @throws Exception
     */
    function getHCLanguagesOptions(string $type = null, array $columns = [])
    {
        $columns[] = 'iso_639_1 as id';
        $columns[] = 'iso_639_1 as label';

        if (!$type) {
            return HCLanguage::select($columns)->get();
        }

        $types = ['content', 'interface'];

        if (!in_array($type, $types)) {
            throw new \Exception('Incorrect given type');
        }

        return HCLanguage::where($type, '1')->select($columns)->get();
    }
}

if (!function_exists('optimizeTranslationOptions')) {
    /**
     * @param Collection|\Illuminate\Support\Collection $list
     * @return Collection|\Illuminate\Support\Collection
     */
    function optimizeTranslationOptions(Collection $list)
    {
        return $list->map(function ($record) {

            return optimizeSingleTranslationOption($record);
        });
    }
}

if (!function_exists('optimizeSingleTranslationOption')) {
    /**
     * @param $record
     * @return mixed
     */
    function optimizeSingleTranslationOption($record): array
    {
        $data['id'] = $record->id;

        if ($record->translation) {
            $data['label'] = $record->translation->label;
        } else {
            $data['label'] = $record->id;
        }

        return $data;
    }
}

if (!function_exists('getHCContentLanguages')) {

    /**
     * Getting available content languages
     *
     * @param bool $asArray
     * @return mixed
     */
    function getHCContentLanguages(bool $asArray = true)
    {
        $available = getHCLanguages('content', $asArray);

        $current = session('content', app()->getLocale());

        if (!$current || !in_array($current, $available)) {
            return $available;
        }

        $reordered = array_diff($available, [$current]);

        array_unshift($reordered, $current);

        return $reordered;
    }
}

if (!function_exists('getHCLanguages')) {
    /**
     * Retrieving languages
     *
     * @param string $key - content, interface
     * @param bool $asArray
     * @return mixed
     */
    function getHCLanguages(string $key, bool $asArray = true)
    {
        $list = HCLanguage::where($key, 1)->get();

        if ($asArray) {
            return $list->pluck('iso_639_1')->toArray();
        }

        return $list;
    }
}


if (!function_exists('array_splice_after_key')) {
    /**
     * https://stackoverflow.com/a/40305210/657451
     *
     * @param $array
     * @param $key
     * @param $array_to_insert
     * @return array
     */
    function array_splice_after_key(&$array, $key, $array_to_insert)
    {
        $key_pos = array_search($key, array_keys($array));
        if ($key_pos !== false) {
            $key_pos++;
            $second_array = array_splice($array, $key_pos);
            $array = array_merge($array, $array_to_insert, $second_array);
        }

        return $array;
    }
}

/*
* @Source: http://eosrei.net/comment/287
*
* Inserts a new key/value before the key in the array.
*
* @param $key
*   The key to insert before.
* @param $array
*   An array to insert in to.
* @param $new_key
*   The key to insert.
* @param $new_value
*   An value to insert.
*
* @return
*   The new array if the key exists, FALSE otherwise.
*
* @see array_insert_after()
*/
if (!function_exists('array_insert_before')) {
    function array_insert_before($key, array &$array, $new_key, $new_value)
    {
        if (array_key_exists($key, $array)) {
            $new = [];
            foreach ($array as $k => $value) {
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
                $new[$k] = $value;
            }

            $array = $new;
        }
    }
}

/*
 * @Source: http://eosrei.net/comment/287
 *
 * Inserts a new key/value after the key in the array.
 *
 * @param $key
 *   The key to insert after.
 * @param $array
 *   An array to insert in to.
 * @param $new_key
 *   The key to insert.
 * @param $new_value
 *   An value to insert.
 *
 * @return
 *   The new array if the key exists, FALSE otherwise.
 *
 * @see array_insert_before()
 */
if (!function_exists('array_insert_after')) {
    function array_insert_after($key, array &$array, $new_key, $new_value)
    {
        if (array_key_exists($key, $array)) {

            $new = [];
            foreach ($array as $k => $value) {
                $new[$k] = $value;
                if ($k == $key) {
                    $new[$new_key] = $new_value;
                }
            }

            $array = $new;
        }
    }
}


