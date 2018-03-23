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
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers\Admin\Acl;

use HoneyComb\Core\Events\Admin\HCRolePermissionUpdated;
use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Requests\Admin\HCRoleRequest;
use HoneyComb\Core\Services\Acl\HCRoleService;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class HCRoleController
 * @package HoneyComb\Core\Http\Controllers\Admin\Acl
 */
class HCRoleController extends HCBaseController
{
    /**
     * @var HCRoleService
     */
    protected $service;
    /**
     * @var HCFrontendResponse
     */
    private $response;

    /**
     * HCRoleController constructor.
     * @param HCRoleService $service
     * @param HCFrontendResponse $response
     */
    public function __construct(HCRoleService $service, HCFrontendResponse $response)
    {
        $this->service = $service;
        $this->response = $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $config = [
            'title' => trans('HCCore::acl_access.page_title'),
            'roles' => json_encode($this->service->getRolesWithPermissions()),
            'permissions' => json_encode($this->service->getAllPermissions()),
            'updateUrl' => route('admin.acl.role.update.permissions'),
        ];

        return view('HCCore::admin.roles', ['config' => $config]);
    }

    /**
     * Update role permissions
     *
     * @param HCRoleRequest $request
     * @return JsonResponse
     */
    public function updatePermissions(HCRoleRequest $request): JsonResponse
    {
        try {
            $message = $this->service->updateRolePermissions(
                $request->input('role_id'),
                $request->input('permission_id')
            );

            event(
                new HCRolePermissionUpdated(
                    $request->input('role_id'),
                    $request->input('permission_id'),
                    $message
                )
            );

        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage());
        }

        return $this->response->success($message);
    }
}
