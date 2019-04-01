<?php

namespace HoneyComb\Core\Services;

use HoneyComb\Core\Contracts\HCConfigServiceContract;
use HoneyComb\Starter\Views\HCDataList;
use HoneyComb\Starter\Views\HCView;

/**
 * Class HCConfigService
 * @package HoneyComb\Core\Services
 */
class HCConfigService implements HCConfigServiceContract
{
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
            ->addConfig('user', auth()->user())
            ->addView($this->getDashBoardView())
            ->toArray();
    }

    /**
     * @return HCView
     */
    protected function getDashBoardView()
    {
        return $this->makeView('dashboard', trans('HCCore::core.label.dashboard'));
    }

    /**
     * @return HCView
     */
    protected function getLoginView(): HCView
    {
        return $this->makeView('login', trans('HCCore::core.label.login'))
            ->addForm('login', 'user-login');
    }

    /**
     * @return HCView
     */
    protected function getRegisterView(): HCView
    {
        return $this->makeView('register', trans('HCCore::core.label.register'))
            ->addForm('register', 'user-register');
    }

    /**
     * @return HCView
     */
    protected function getPasswordRemindView(): HCView
    {
        return $this->makeView('password-remind', trans('HCCore::core.label.forget_password'))
            ->addForm('password-remind', 'password-remind');
    }

    /**
     * @return HCView
     */
    protected function getPasswordResetView(): HCView
    {
        return $this->makeView('password-reset', trans('HCCore::core.label.reset_password'))
            ->addForm('password-reset', 'password-reset');
    }

    /**
     * @param string $key
     * @param string|null $label
     * @return HCView
     */
    protected function makeView(string $key, string $label = null): HCView
    {
        return new HCView($key, $label);
    }

    /**
     * @param string $key
     * @param string $source
     * @return HCDataList
     */
    protected function makeDataList(string $key, string $source): HCDataList
    {
        return new HCDataList($key, $source);
    }
}