<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Auth\Notifications\ResetPassword;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";

            // Daftarkan middleware Spatie Permission
            $this->registerMiddleware();
        });
    }

    /**
     * Mendaftarkan Middleware yang diperlukan.
     */
    protected function registerMiddleware(): void
    {
        $router = app(Registrar::class);

        // Alias middleware untuk penggunaan dalam route
        $router->aliasMiddleware('role', RoleMiddleware::class);
        $router->aliasMiddleware('permission', PermissionMiddleware::class);
    }

}
