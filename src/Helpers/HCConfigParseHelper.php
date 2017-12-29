<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Helpers;

use Illuminate\Filesystem\Filesystem;

class HCConfigParseHelper
{
    /**
     * Scan folders for honeycomb configuration files
     *
     * @return array
     */
    public function getConfigFilesSorted()
    {
        $fileSystem = new Filesystem();

        $projectConfig = $fileSystem->glob(app_path('hc-config.json'));
        $packageConfigs = $fileSystem->glob(__DIR__ . '/../../../../*/*/*/hc-config.json');

        $packageConfigs = $this->sortByPriority($packageConfigs);

        $files = array_merge($packageConfigs, $projectConfig);

        return $files;
    }

    /**
     * Sort hc-config.json files by sequence
     *
     * @param array $filePaths
     * @return array
     */
    private function sortByPriority(array $filePaths): array
    {
        $toSort = [];

        foreach ($filePaths as $filePath) {
            $file = json_decode(file_get_contents($filePath), true);

            $sequence = array_get($file, 'general.sequence', 0);

            $toSort[$sequence][] = $filePath;
        }

        ksort($toSort);

        return array_collapse($toSort);
    }
}
