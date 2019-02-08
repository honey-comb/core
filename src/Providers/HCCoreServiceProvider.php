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

namespace HoneyComb\Core\Providers;

use HoneyComb\Core\Console\HCCreateSuperAdminCommand;
use HoneyComb\Core\Console\HCGenerateAdminMenuCommand;
use HoneyComb\Core\Console\HCGenerateFormsCommand;
use HoneyComb\Core\Console\HCProjectSize;
use HoneyComb\Core\Console\HCScanRolePermissionsCommand;
use HoneyComb\Core\Console\HCSeedCommand;
use HoneyComb\Core\Console\HCUpdate;
use HoneyComb\Core\Http\Middleware\HCAclAuthenticate;
use HoneyComb\Core\Http\Middleware\HCAclPermissionsMiddleware;
use HoneyComb\Core\Http\Middleware\HCCheckSelectedLanguage;
use HoneyComb\Core\Models\Acl\HCAclPermission;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\Acl\HCPermissionRepository;
use HoneyComb\Core\Repositories\Acl\HCRoleRepository;
use HoneyComb\Core\Repositories\HCBaseRepository;
use HoneyComb\Core\Repositories\HCLanguageRepository;
use HoneyComb\Core\Repositories\HCUserRepository;
use HoneyComb\Core\Repositories\Users\HCPersonalInfoRepository;
use HoneyComb\Core\Repositories\Users\HCUserActivationRepository;
use HoneyComb\Core\Services\Acl\HCRoleService;
use HoneyComb\Core\Services\HCUserActivationService;
use HoneyComb\Core\Services\HCUserService;
use HoneyComb\Starter\Providers\HCBaseServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Routing\Router;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider;

/**
 * Class HCCoreServiceProvider
 * @package HoneyComb\Core\Providers
 */
class HCCoreServiceProvider extends HCBaseServiceProvider
{
    /**
     * @var string
     */
    protected $homeDirectory = __DIR__;

    /**
     * @var array
     */
    protected $commands = [
        HCScanRolePermissionsCommand::class,
        HCGenerateAdminMenuCommand::class,
        HCGenerateFormsCommand::class,
        HCCreateSuperAdminCommand::class,
        HCSeedCommand::class,
        HCProjectSize::class,
        HCUpdate::class,
    ];

    /**
     * Namespace
     *
     * @var string
     */
    protected $namespace = 'HoneyComb\Core\Http\Controllers';

    /**
     * Provider name
     *
     * @var string
     */
    protected $packageName = 'HCCore';

    /**
     * @param Router $router
     * @throws \Exception
     */
    public function boot(Router $router): void
    {
        parent::boot($router);

        $this->loadViews();

        $this->registerGateItems(app()->make(Gate::class));

        $this->registerMiddleware($router);
    }

    /**
     *
     */
    protected function registerPublishes(): void
    {
        parent::registerPublishes();
//
//        $this->publishes([
//            $this->packagePath('resources/assets') => resource_path('assets/honey-comb'),
//        ], 'hc-assets');
//
//        $this->publishes([
//            $this->packagePath('resources/public') => public_path('honey-comb'),
//        ], 'public');
    }

    /**
     *
     */
    public function register(): void
    {
        // register LogViewer service provider
        if (class_exists(LaravelLogViewerServiceProvider::class)) {
            $this->app->register(LaravelLogViewerServiceProvider::class);
        }

        $this->mergeConfigFrom(
            $this->packagePath('config/hc.php'),
            'hc'
        );

        $this->mergeConfigFrom(
            $this->packagePath('config/services.php'),
            'services'
        );

        $this->registerRepositories();

        $this->registerServices();
    }

    /**
     * Register acl permissions
     *
     * @param Gate $gate
     * @throws \Exception
     */
    private function registerGateItems(Gate $gate): void
    {
        $gate->before(function (HCUser $user) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        $permissions = $this->getPermissions();

        if (!is_null($permissions)) {
            foreach ($permissions as $permission) {
                $gate->define($permission->action, function (HCUser $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
        }
    }

    /**
     * @param Router $router
     */
    private function registerMiddleware(Router $router): void
    {
        $ignore = config('hc.ignoreDefaultMiddleware.' . $this->packageName, []);

        if (!in_array(HCACLPermissionsMiddleware::class, $ignore)) {
            $router->aliasMiddleware('acl', HCACLPermissionsMiddleware::class);
        }

        if (!in_array(HCCheckSelectedLanguage::class, $ignore)) {
            $router->pushMiddleWareToGroup('api', HCCheckSelectedLanguage::class);
        }
    }


    /**
     * Get permissions
     *
     * @return null|Collection
     * @throws \Exception
     */
    private function getPermissions(): ? Collection
    {
        if (!cache()->has('hc-permissions')) {
            try {
                if (class_exists(HCAclPermission::class) && Schema::hasTable(HCAclPermission::getTableName())) {
                    $expiresAt = Carbon::now()->addHour(12);

                    $permissions = HCAclPermission::with('roles')->get();

                    cache()->put('hc-permissions', $permissions, $expiresAt);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return cache()->get('hc-permissions');
    }

    /**
     *
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(HCUserRepository::class);
        $this->app->singleton(HCRoleRepository::class);
        $this->app->singleton(HCPermissionRepository::class);
        $this->app->singleton(HCPersonalInfoRepository::class);
        $this->app->singleton(HCUserActivationRepository::class);
        $this->app->singleton(HCLanguageRepository::class);
    }

    /**
     *
     */
    private function registerServices(): void
    {
        $this->app->singleton(HCUserService::class);
        $this->app->singleton(HCUserActivationService::class);
        $this->app->singleton(HCRoleService::class);
    }
}
