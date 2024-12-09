<?php

namespace App\Providers;

use App\Nova\User;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Nova\Dashboards\Main as MainDashboard;
use Sereny\NovaPermissions\Nova\Permission;
use Sereny\NovaPermissions\Nova\Role;
use Sereny\NovaPermissions\NovaPermissions as NovaPermissionsTool;
use App\Models\User as UserModel;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        Nova::footer(fn() => null);

        Nova::withoutGlobalSearch();

        Nova::mainMenu(function () {
            return [
                MenuSection::dashboard(MainDashboard::class)->icon("home"),

                MenuSection::make("Access", [
                    MenuItem::resource(User::class),
                    MenuItem::resource(Role::class),
                    MenuItem::resource(Permission::class),
                ], "key")->collapsedByDefault()
                    ->canSee(fn(UserModel $user) => $user->is_admin),
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes(): void
    {
        Nova::routes()
                ->withAuthenticationRoutes(default: true)
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate(): void
    {
        Gate::define('viewNova', fn () => true);
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards(): array
    {
        return [new MainDashboard];
    }

    public function tools(): array
    {
        return [
            (new NovaPermissionsTool())
                ->hideFieldsFromPermission(["guard_name"])
                ->hideFieldsFromRole(["guard_name"])
                ->disablePermissions()
                ->disableMenu()
                ->rolePolicy(RolePolicy::class)
                ->permissionPolicy(PermissionPolicy::class)
                ->roleResource(\App\Nova\Role::class)
                ->permissionResource(\App\Nova\Permission::class),
        ];
    }
}
