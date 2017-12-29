<?php
/**
 * @copyright 2017 interactivesolutions
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

namespace HoneyComb\Core\Models\Traits;

use HoneyComb\Core\Notifications\HCActivationLink;
use HoneyComb\Core\Services\HCUserActivationService;

/**
 * Trait ActivateUser
 * @package HoneyComb\Core\Models\Traits
 */
trait HCActivateUser
{
    /**
     * Check if user is activated
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return !!$this->activated_at;
    }

    /**
     * Check if user is not activated
     *
     * @return bool
     */
    public function isNotActivated(): bool
    {
        return !$this->isActivated();
    }

    /**
     * Create and send user activation
     */
    public function createTokenAndSendActivationCode(): void
    {
        $activationService = app(HCUserActivationService::class);

        $activationService->sendActivationMail($this);
    }

    /**
     * Send the activation link notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendActivationLinkNotification($token): void
    {
        $this->notify(new HCActivationLink($token));
    }

    /**
     * Activate account
     */
    public function activate(): void
    {
        $this->activated_at = $this->freshTimestamp();
        $this->save();
    }
}
