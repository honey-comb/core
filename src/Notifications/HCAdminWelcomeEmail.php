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


namespace HoneyComb\Core\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


/**
 * Class HCAdminWelcomeEmail
 * @package HoneyComb\Core\Notifications
 */
class HCAdminWelcomeEmail extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $authRoute;

    /**
     * Send password holder
     *
     * @var
     */
    private $sendPassword;

    /**
     * Create a notification instance.
     *
     * @param $authRoute
     */
    public function __construct(string $authRoute = 'auth.index')
    {
        $this->authRoute = $authRoute;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->view('HCCore::emails.template')
            ->subject(trans('HCCore::user.welcome_email.subject'))
            ->greeting(trans('HCCore::user.welcome_email.greeting'))
            ->line(trans('HCCore::user.welcome_email.text'))
            ->line(trans('HCCore::user.welcome_email.show_email', ['email' => $notifiable->email]));

        if ($this->sendPassword) {
            $message->line(trans('HCCore::user.welcome_email.show_password', ['password' => $this->sendPassword]));
        }

        $message->action(trans('HCCore::user.welcome_email.login_link'), route($this->authRoute));

        if (is_null($notifiable->activated_at)) {
            $message->line(trans('HCCore::user.welcome_email.activation_required'));
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [];
    }

    /**
     * Sent reset password
     *
     * @param string $password
     * @return $this
     */
    public function withPassword(string $password)
    {
        $this->sendPassword = $password;

        return $this;
    }
}
