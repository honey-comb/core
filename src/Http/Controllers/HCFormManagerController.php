<?php
declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers;

use Cache;
use Illuminate\Http\JsonResponse;

/**
 * Class HCFormManagerController
 * @package HoneyComb\Core\http\controllers
 */
class HCFormManagerController extends HCBaseController
{
    /**
     * Get form structure as json object
     *
     * @param string $key
     * @return JsonResponse
     * @throws \Exception
     */
    public function getStructure(string $key): JsonResponse
    {
        return response()->json(
            $this->getForm($key)
        );
    }

    /**
     * Get form structure as a json string
     *
     * @param string $key
     * @return string
     * @throws \Exception
     */
    public function getStructureAsString(string $key): string
    {
        return json_encode($this->getForm($key));
    }

    /**
     * Get form from cache or get it from class and than store it to cache
     *
     * @param string $key
     * @return array
     * @throws \Exception
     */
    private function getForm(string $key): array
    {
        if (!cache()->has('hc-forms')) {
            \Artisan::call('hc:forms');
        }

        $formHolder = cache()->get('hc-forms');

        $new = substr($key, 0, -4);
        $edit = substr($key, 0, -5);

        if (array_has($formHolder, $new)) {
            $form = app()->make($formHolder[$new]);

            return $form->createForm();
        }

        if (array_has($formHolder, $edit)) {
            $form = app()->make($formHolder[$edit]);

            return $form->createForm(true);
        }

        throw new \Exception('Form not found: ' . $key);
    }
}
