<?php namespace CoasterCms;

use CoasterCms\Auth\CoasterGuard;
use CoasterCms\Auth\CoasterUserProvider;
use CoasterCms\Croppa\Url;
use CoasterCms\Events\Cms\LoadAuth;
use CoasterCms\Events\Cms\LoadMiddleware;
use CoasterCms\Events\Cms\SetViewPaths;
use CoasterCms\Http\Middleware\AdminAuth;
use CoasterCms\Http\Middleware\GuestAuth;
use CoasterCms\Http\Middleware\SecureUpload;
use CoasterCms\Http\Middleware\UploadChecks;
use CoasterCms\Libraries\Builder\FormMessage;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class CmsServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @param Router $router
     * @param Kernel $kernel
     * @return void
     */
    public function boot(Router $router, Kernel $kernel)
    {
        // publishable config
        $this->publishes([
            COASTER_ROOT . '/config/coaster.php' => config_path('coaster.php'),
        ], 'coaster.config');

        // add router middleware
        $globalMiddleware = [
            UploadChecks::class
        ];
        $routerMiddleware = [
            'coaster.cms' => [],
            'coaster.admin' => [AdminAuth::class],
            'coaster.guest' => [GuestAuth::class],
            'coaster.secure_upload' => [SecureUpload::class],
        ];
        event(new LoadMiddleware($globalMiddleware, $routerMiddleware));
        foreach ($globalMiddleware as $globalMiddlewareClass) {
            $kernel->pushMiddleware($globalMiddlewareClass);
        }
        foreach ($routerMiddleware as $routerMiddlewareName => $routerMiddlewareClass) {
            $router->middlewareGroup($routerMiddlewareName, $routerMiddlewareClass);
        }

        // load coaster views
        $adminViews = [
            rtrim(config('coaster.admin.view'), '/')
        ];
        $frontendViews = [
            rtrim(config('coaster.frontend.view'), '/')
        ];
        event(new SetViewPaths($adminViews, $frontendViews));
        $this->loadViewsFrom($adminViews, 'coaster');
        $this->loadViewsFrom($frontendViews, 'coasterCms');

        $this->app->singleton('formMessage', function () {
            return new FormMessage($this->app['session'], 'default', config('coaster.frontend.form_error_class'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if (!defined('COASTER_ROOT')) {
            define('COASTER_ROOT', dirname(__DIR__));
        }

        // register third party providers (above config provider as that contains config overrides)
        $this->app->register('Bkwld\Croppa\ServiceProvider');
        $this->app->register('Collective\Html\HtmlServiceProvider');

        // register coaster providers
        $this->app->register('CoasterCms\Providers\CoasterEventsProvider');
        $this->app->register('CoasterCms\Providers\CoasterConfigProvider');
        $this->app->register('CoasterCms\Providers\CoasterConsoleProvider');
        $this->app->register('CoasterCms\Providers\CoasterPageBuilderProvider');

        // add coater guard & provider
        /** @var AuthManager $authManager */
        $authManager = $this->app['auth'];
        $this->app['config']->set('auth.providers.coaster-user', ['driver' => 'coaster-provider', 'model' => \CoasterCms\Models\User::class]);
        $authManager->extend('coaster-guard', function ($app, $name, $config) {
            $provider = $app['auth']->createUserProvider($config['provider'] ?? null);
            $guard = new CoasterGuard($name, $provider, $app['session.store']);
            // replicate createSessionDriver in AuthManager
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($this->app['cookie']);
            }
            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($this->app['events']);
            }
            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
            }
            return $guard;
        });
        $authManager->provider('coaster-provider', function ($app, $config) {
            return new CoasterUserProvider($app['hash'], $config['model']);
        });

        // Overwrite Croppa Url
        $this->app->singleton('Bkwld\Croppa\URL', function($app) {
            $config = $this->app->make('config')->get('croppa');
            if (isset($config['signing_key']) && $config['signing_key'] == 'app.key') {
                $config['signing_key'] = $this->app->make('config')->get('app.key');
            }
            return new Url($config);
        });

        // register aliases
        $loader = AliasLoader::getInstance();
        $loader->alias('Form', 'Collective\Html\FormFacade');
        $loader->alias('HTML', 'Collective\Html\HtmlFacade');
        $loader->alias('CmsBlockInput', 'CoasterCms\Helpers\Cms\View\CmsBlockInput');
        $loader->alias('FormMessage', 'CoasterCms\Facades\FormMessage');
        $loader->alias('AssetBuilder', 'CoasterCms\Libraries\Builder\AssetBuilder');
        $loader->alias('DateTimeHelper', 'CoasterCms\Helpers\Cms\DateTimeHelper');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'CoasterCms\Providers\CoasterConfigProvider',
            'CoasterCms\Providers\CoasterEventsProvider',
            'CoasterCms\Providers\CoasterConsoleProvider',
            'CoasterCms\Providers\CoasterPageBuilderProvider',
            'Bkwld\Croppa\ServiceProvider',
            'Collective\Html\HtmlServiceProvider'
        ];
    }

}
