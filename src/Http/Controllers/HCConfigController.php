<?php

namespace HoneyComb\Core\Http\Controllers;

use HoneyComb\Core\Contracts\HCConfigServiceContract;
use HoneyComb\Core\Services\HCConfigService;
use HoneyComb\Starter\Exceptions\HCException;
use HoneyComb\Starter\Helpers\HCResponse;
use Illuminate\Http\JsonResponse;

/**
 * Class HCConfigController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCConfigController extends HCBaseController
{
    /**
     * @var HCResponse
     */
    private $response;

    /**
     * HCConfigController constructor.
     * @param HCResponse $response
     */
    public function __construct(HCResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return JsonResponse
     *
     * @throws HCException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getInitial(): JsonResponse
    {
        $service = $this->getConfigService();

        if (auth()->check()) {
            return $this->response->success('OK', $service->getUserConfig());
        }

        return $this->response->success('OK', $service->getGuestConfig());
    }

    /**
     * @return mixed|HCConfigService
     *
     * @throws HCException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getConfigService()
    {
        $className = config('hc.config_service');
        $service = app()->make($className);

        if (!$service instanceof HCConfigServiceContract) {
            throw new HCException(
                'Class ' . $className . ' must be instance of HoneyComb\\Core\\Contracts\\HCConfigServiceContract'
            );
        }

        return $service;
    }
}
