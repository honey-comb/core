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

namespace HoneyComb\Core\Http\Controllers;

use HoneyComb\Core\Events\Admin\HCUserForceDeleted;
use HoneyComb\Core\Events\Admin\HCUserRestored;
use HoneyComb\Core\Events\Admin\HCUserSoftDeleted;
use HoneyComb\Core\Http\Requests\HCUserRequest;
use HoneyComb\Core\Services\HCUserService;
use HoneyComb\Starter\Helpers\HCResponse;
use HoneyComb\Starter\Views\HCDataTable;
use HoneyComb\Starter\Views\HCDataTableHeader;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;

/**
 * Class HCUserController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCUserController extends HCBaseController
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var HCUserService
     */
    protected $service;

    /**
     * @var HCResponse
     */
    protected $response;

    /**
     * HCUsersController constructor.
     * @param Connection $connection
     * @param HCUserService $service
     * @param HCResponse $response
     */
    public function __construct(Connection $connection, HCUserService $service, HCResponse $response)
    {
        $this->connection = $connection;
        $this->service = $service;
        $this->response = $response;
    }

    /**
     * Admin panel page config
     *
     * @return JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index(): JsonResponse
    {
        $config = $this->makeView('user-view', trans('HCCore::users.title.list'))
            ->addFormSource('add-new', 'user')
            ->addDataTable($this->getDataList())
            ->setPermissions($this->getUserActions('honey_comb_core_user'))
            ->toArray();

        return $this->response->success('OK', $config);
    }

    /**
     * Store record
     *
     * @param HCUserRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(HCUserRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $record = $this->service->createUser(
                $request->getUserInput(),
                $request->getPersonalData(),
                $request->getRoles(),
                $request->filled('send_welcome_email'),
                $request->filled('send_password')
            );

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            report($exception);

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success(trans('HCCore::users.message.user_updated'), $record);
    }

    /**
     * Update record
     *
     * @param HCUserRequest $request
     * @param string $id
     * @return mixed
     * @throws \Exception
     */
    public function update(HCUserRequest $request, string $id): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $record = $this->service->updateUser(
                $id,
                $request->getUserInput(),
                $request->getRoles(),
                $request->getPersonalData()
            );

            if ($request->wantToActivate()) {
                $this->service->activateUser($record->id);
            }

            $this->connection->commit();

        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            report($exception);

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success(trans('HCCore::users.message.user_updated'), $record);
    }

    /**
     * Creating data list
     * @param HCUserRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCUserRequest $request): JsonResponse
    {
        return $this->response->success('OK', $this->service->getRepository()->getListPaginate($request));
    }

    /**
     * Creating data list
     * @param HCUserRequest $request
     * @return JsonResponse
     */
    public function getOptions(HCUserRequest $request): JsonResponse
    {
        return $this->response->success('OK', $this->service->getRepository()->getOptions($request));
    }

    /**
     * Getting single record
     *
     * @param string $recordId
     * @return JsonResponse
     */
    public function getById(string $recordId): JsonResponse
    {
        return $this->response->success('OK', $this->service->getUserById($recordId));
    }

    /**
     * @param HCUserRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteSoft(HCUserRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->deleteSoft($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            report($exception);

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success(trans('HCCore::users.message.user_deleted'));
    }

    /**
     * @param HCUserRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteForce(HCUserRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->deleteForce($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            report($exception);

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success(trans('HCCore::users.message.user_deleted'));
    }

    /**
     * @param HCUserRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function restore(HCUserRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->restore($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            report($exception);

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success(trans('HCCore::users.message.user_restored'));
    }

    /**
     * Get admin page table columns settings
     *
     * @return HCDataTable
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getDataList(): HCDataTable
    {
        return $this->makeDataTable('user', route('v1.api.users.list'))
            ->addHeader('email', trans('HCCore::users.label.email'))
            ->addHeader('last_login', trans('HCCore::users.label.last_login'))
            ->addHeader('last_activity', trans('HCCore::users.label.last_activity'))
            ->addHeader('activated_at', trans('HCCore::users.label.activated_at'));
    }
}
