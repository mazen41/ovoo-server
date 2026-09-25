<?php

use App\Http\Exception\ExceptionHandler;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckStatus;
use App\Http\Middleware\KycMiddleware;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\RedirectIfAdmin;
use App\Http\Middleware\RedirectIfNotAdmin;
use App\Http\Middleware\RegistrationStep;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\ActiveTemplateMiddleware;
use App\Http\Middleware\Demo;
use App\Http\Middleware\HasAgentPermission;
use App\Http\Middleware\HasMetaWhatsapp;
use App\Http\Middleware\HasSubscription;
use App\Http\Middleware\IsParentUser;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\VerifyCsrfToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::namespace('App\Http\Controllers')->group(function () {
                Route::prefix('api')
                    ->middleware(['api', 'maintenance'])
                    ->group(base_path('routes/api.php'));
                Route::prefix('external-api')
                    ->namespace('ExternalApi')
                    ->middleware(['maintenance'])
                    ->group(base_path('routes/external_api.php'));
                Route::middleware(['web'])
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));
                Route::middleware(['web', 'maintenance'])
                    ->namespace('Gateway')
                    ->prefix('ipn')
                    ->name('ipn.')
                    ->group(base_path('routes/ipn.php'));

                Route::middleware(['web', 'maintenance'])->prefix('user')->group(base_path('routes/user.php'));
                Route::middleware(['web', 'maintenance'])->group(base_path('routes/web.php'));
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            SubstituteBindings::class,
            LanguageMiddleware::class,
            ActiveTemplateMiddleware::class,
            SubstituteBindings::class,
            VerifyCsrfToken::class,
        ]);
        $middleware->alias([
            'admin'       => RedirectIfNotAdmin::class,
            'admin.guest' => RedirectIfAdmin::class,

            'maintenance'           => MaintenanceMode::class,
            'registration.complete' => RegistrationStep::class,
            'demo'                  => Demo::class,

            'auth'                   => Authenticate::class,
            'check.status'           => CheckStatus::class,
            'kyc'                    => KycMiddleware::class,
            'guest'                  => RedirectIfAuthenticated::class,

            'has.subscription' => HasSubscription::class,
            'has.whatsapp'     => HasMetaWhatsapp::class,
            'agent.permission' => HasAgentPermission::class,
            'parent.user'      => IsParentUser::class,
            'permission'       => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(
            except: ['user/deposit', 'ipn*']
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e, Request $request) {


            //for handle custom 
            if (str_starts_with($e->getMessage(), "custom_not_found_exception") || isApiRequest() || isExternalApiRequest()) {
                $exceptionManager = new ExceptionHandler();

                //handle for custom not found 
                if (str_starts_with($e->getMessage(), "custom_not_found_exception")) {
                    return $exceptionManager->exceptionShowForCustomNotFound($e);
                }
    
                //handler for exception
                if (isApiRequest() || isExternalApiRequest()) {
                    return $exceptionManager->exceptionShowApi($e);
                }
            }


            if (request()->ajax()) {
                return apiResponse('exception', 'error', [$e->getMessage()]);
            }
        });
    })->create();
