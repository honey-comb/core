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

namespace HoneyComb\Core\Http\Controllers\Acl;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Requests\HCRoleRequest;
use HoneyComb\Core\Services\Acl\HCRoleService;
use HoneyComb\Starter\Exceptions\HCException;
use HoneyComb\Starter\Helpers\HCResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;

/**
 * Class HCRoleController
 * @package HoneyComb\Core\Http\Controllers\Acl
 */
class HCRoleController extends HCBaseController
{
    /**
     * @var HCRoleService
     */
    protected $service;
    /**
     * @var HCResponse
     */
    protected $response;
    /**
     * @var Connection
     */
    private $connection;

    /**
     * HCRoleController constructor.
     * @param Connection $connection
     * @param HCRoleService $service
     * @param HCResponse $response
     */
    public function __construct(Connection $connection, HCRoleService $service, HCResponse $response)
    {
        $this->service = $service;
        $this->response = $response;
        $this->connection = $connection;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $config = [
            'title' => trans('HCCore::roles.title.list'),
            'roles' => json_encode($this->service->getRolesWithPermissions()),
            'permissions' => json_encode($this->service->getAllPermissions()),
            'updateUrl' => route('v1.api.users.roles.update_permissions'),
        ];

        return $this->response->success('OK', $config);
    }

    /**
     * @param HCRoleRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function updatePermissions(HCRoleRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $message = $this->service->updateRolePermissions(
                $request->input('role_id'),
                $request->input('permission_id')
            );

            $this->connection->commit();
        } catch (HCException $exception) {
            $this->connection->rollBack();

            report($exception);

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success($message);
    }
}
