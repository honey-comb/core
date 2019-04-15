<?php

namespace HoneyComb\Core\Services;

use HoneyComb\Core\Contracts\HCConfigServiceContract;
use HoneyComb\Starter\Contracts\HCDataTableContract;
use HoneyComb\Starter\Contracts\HCViewContract;
use HoneyComb\Starter\Views\HCDataTable;
use HoneyComb\Starter\Views\HCView;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HCConfigService
 * @package HoneyComb\Core\Services
 */
class HCConfigService implements HCConfigServiceContract
{
    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return auth()->check();
    }

    /**
     * @return Model|null
     */
    public function getUser(): ?Model
    {
        return auth()->user();
    }

    /**
     * @return array
     */
    public function getGuestConfig(): array
    {
        $authView = $this->makeView('auth')
            ->addView($this->getLoginView())
            ->addView($this->getPasswordRemindView())
            ->addView($this->getPasswordResetView());

        if (config('hc.allow_registration')) {
            $authView->addView($this->getRegisterView());
        }

        return $this->makeView('core')->addView($authView)->toArray();
    }

    /**
     * @return array
     */
    public function getUserConfig(): array
    {
        return $this->makeView('core')
            ->addConfig('user', $this->getUser())
            ->addView($this->getDashBoardView())
            ->toArray();
    }

    /**
     * @return HCViewContract
     */
    protected function getDashBoardView(): HCViewContract
    {
        return $this->makeView('dashboard', trans('HCCore::core.label.dashboard'));
    }

    /**
     * @return HCViewContract
     */
    protected function getLoginView(): HCViewContract
    {
        return $this->makeView('login', trans('HCCore::core.label.login'))
            ->addFormSource('login', 'user-login');
    }

    /**
     * @return HCViewContract
     */
    protected function getRegisterView(): HCViewContract
    {
        return $this->makeView('register', trans('HCCore::core.label.register'))
            ->addFormSource('register', 'user-register');
    }

    /**
     * @return HCViewContract
     */
    protected function getPasswordRemindView(): HCViewContract
    {
        return $this->makeView('password-remind', trans('HCCore::core.label.forget_password'))
            ->addFormSource('password-remind', 'password-remind');
    }

    /**
     * @return HCViewContract
     */
    protected function getPasswordResetView(): HCViewContract
    {
        return $this->makeView('password-reset', trans('HCCore::core.label.reset_password'))
            ->addFormSource('password-reset', 'password-reset');
    }

    /**
     * @param string $key
     * @param string|null $label
     * @return HCViewContract
     */
    protected function makeView(string $key, string $label = null): HCViewContract
    {
        return new HCView($key, $label);
    }

    /**
     * @param string $key
     * @param string|null $source
     * @return HCDataTableContract
     */
    protected function makeDataTable(string $key, string $source = null): HCDataTableContract
    {
        return new HCDataTable($key, $source);
    }
}