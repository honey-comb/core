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

namespace HoneyComb\Core\Services;

use HoneyComb\Core\Events\HCUserActivated;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\HCUserRepository;
use HoneyComb\Core\Repositories\Users\HCUserActivationRepository;
use HoneyComb\Starter\Exceptions\HCException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class HCUserActivationService
 * @package HoneyComb\Core\Services
 */
class HCUserActivationService
{
    /**
     * @var HCUserActivationRepository
     */
    protected $hcUserActivationRepository;
    /**
     * @var HCUserRepository
     */
    protected $hcUserRepository;

    /**
     * UserActivationService constructor.
     * @param HCUserActivationRepository $activationRepository
     * @param HCUserRepository $userRepository
     */
    public function __construct(HCUserActivationRepository $activationRepository, HCUserRepository $userRepository)
    {
        $this->hcUserActivationRepository = $activationRepository;
        $this->hcUserRepository = $userRepository;
    }

    /**
     * @param HCUser $user
     * @param int $resendAfter
     * @return string
     */
    public function sendActivationMail(HCUser $user, int $resendAfter = 24): string
    {
        if (!$this->shouldSend($user, $resendAfter)) {
            return trans('HCCore::user.activation.check_email');
        }

        $token = $this->createActivation($user->id);

        $user->sendActivationLinkNotification($token);

        return trans('HCCore::user.activation.resent_activation');
    }

    /**
     * @param string $token
     * @return HCUser
     * @throws \Exception
     */
    public function activateUser(string $token): HCUser
    {
        $activation = $this->hcUserActivationRepository->getActivationByToken($token);

        if ($activation === null) {
            throw new HCException(trans('HCCore::user.activation.bad_token'));
        }

        try {
            $user = $this->hcUserRepository->findById($activation->user_id);
        } catch (ModelNotFoundException $exception) {
            throw new HCException(trans('HCCore::user.activation.user_not_found'));
        }

        // activate user
        $user->activate();

        // delete activation code
        $this->hcUserActivationRepository->deleteActivation($token);

        event(new HCUserActivated($user));

        return $user;
    }

    /**
     * @param string $userId
     * @return string
     */
    protected function createActivation(string $userId): string
    {
        $activation = $this->hcUserActivationRepository->getActivation($userId);


        if (!$activation) {
            return $this->createToken($userId);
        }

        return $this->regenerateToken($userId);
    }

    /**
     * @param string $userId
     * @return string
     */
    protected function createToken(string $userId): string
    {
        $token = $this->getToken();

        $this->hcUserActivationRepository->insertActivation($userId, $token);

        return $token;
    }

    /**
     * @return string
     */
    protected function getToken(): string
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * @param $user
     * @param int $resendAfter
     * @return bool
     */
    protected function shouldSend($user, int $resendAfter = 24): bool
    {
        $activation = $this->hcUserActivationRepository->getActivation($user->id);

        return $activation === null || $activation->created_at->timestamp + 60 * 60 * $resendAfter < time();
    }

    /**
     * @param string $userId
     * @return string
     */
    protected function regenerateToken(string $userId): string
    {
        $token = $this->getToken();

        $this->hcUserActivationRepository->updateUserActivations($userId, $token);

        return $token;
    }
}
