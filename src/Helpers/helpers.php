<?php

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
