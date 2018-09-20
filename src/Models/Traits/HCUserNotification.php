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

use HoneyComb\Core\Models\Users\HCUserNotificationType;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HCUserNotificationSubscription
 * @package HoneyComb\Core\Models\Traits
 */
trait HCUserNotificationSubscription
{
    /**
     * A user may have multiple notifications.
     *
     * @return BelongsToMany
     */
    public function notification_subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(HCUserNotificationType::class, 'hc_user_notification', 'user_id', 'type_id');
    }

    /**
     * Create notifications for user
     *
     * @param array $notifications - notification ids
     */
    public function assignNotifications(array $notifications): void
    {
        if (!empty($notifications)) {
            $this->notifications()->sync($notifications);
        }
    }

    /**
     * @return array
     */
    public function notification_list(): array
    {
        return $this->notifications()->map(function ($item) {
            return [
                'id' => $item->id,
                'label' => trans($item->translation_key),
            ];
        })->toArray();
    }
}
