<?php
/**
 * @copyright 2018 innovationbase
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

use HoneyComb\Core\DTO\HCAuthorizeDTO;
use HoneyComb\Core\Events\HCSocialiteAuthUserLoggedIn;
use HoneyComb\Starter\Exceptions\HCException;
use HoneyComb\Starter\Helpers\HCResponse;
use HoneyComb\Core\Http\Requests\HCAuthLoginRequest;
use HoneyComb\Core\Http\Requests\HCAuthRegisterRequest;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Services\HCUserService;
use GuzzleHttp\Client;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

/**
 * Class HCAuthController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCAuthController extends HCBaseController
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var HCResponse
     */
    private $response;

    /**
     * @var HCUserService
     */
    private $userService;

    /**
     * WBannerController constructor.
     * @param Connection $connection
     * @param HCResponse $response
     * @param HCUserService $userService
     */
    public function __construct(
        Connection $connection,
        HCResponse $response,
        HCUserService $userService
    ) {
        $this->connection = $connection;
        $this->response = $response;
        $this->userService = $userService;
    }

    /**
     * @param HCAuthLoginRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function login(HCAuthLoginRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            if ($request->isSocialProvider()) {
                $user = $this->getSocialUser(
                    $request->input('access_token'),
                    $request->input('provider')
                );

                /** @var HCUser $user */
                $user = $this->userService->getRepository()->findOrFail($user->id);

                event(new HCSocialiteAuthUserLoggedIn($user,  $request->input('provider')));
            } else {
                if (!auth()->attempt($request->only(['email', 'password']))) {
                    return $this->response->error(trans('auth.bad_credentials'), [], 401);
                }

                $user = $request->user();
            }

            $token = $user->createToken('Personal Access Token');

            $this->connection->commit();
        } catch (HCException $exception) {
            $this->connection->rollback();

            return $this->response->error($exception->getMessage());
        } catch (\Throwable $exception) {
            $this->connection->rollback();

            report($exception);

            return $this->response->error(trans('project.server_error'));
        }

        $user->updateLastLogin();

        return $this->response->success('OK', (new HCAuthorizeDTO($user, $token))->toArray());
    }

    /**
     * @param HCAuthRegisterRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function register(HCAuthRegisterRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $user = $this->userService->registerUser(
                $request->input('email'),
                $request->input('password'),
                $request->input('first_name'),
                $request->input('last_name') ? $request->input('last_name') : ''
            );

            $token = $user->createToken('Personal Access Token');

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollback();

            report($exception);

            return $this->response->error(trans('project.server_error'));
        }

        $user->updateLastLogin();

        return $this->response->success('OK', (new HCAuthorizeDTO($user, $token))->toArray());
    }

    /**
     * @param string $accessToken
     * @param string $provider
     * @return HCUser
     * @throws \Throwable
     */
    private function getSocialUser(string $accessToken, string $provider): HCUser
    {
        $user = Socialite::driver($provider)->userFromToken($accessToken);

        if (is_null($user->email)) {
            if ($provider == 'facebook') {
                $this->deAuthorize($user);
            }

            throw new HCException('Email is required');
        }

        return $this->userService->createOrUpdateUserProvider($user, $provider);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var HCUser $user */
        $user = $request->user();
        $user->updateLastActivity();
        $user->token()->revoke();

        return $this->response->success('Successfully logged out');
    }

    /**
     * DeAuthorize user from app
     *
     * @param User $user
     * @return void
     */
    private function deAuthorize(User $user): void
    {
        $client = new Client;

        $client->delete("https://graph.facebook.com/{$user->id}/permissions",
            [
                'headers' => ['Accept' => 'application/json'],
                'form_params' => [
                    'access_token' => $user->token,
                ],
            ]
        );
    }
}
