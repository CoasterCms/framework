<?php namespace CoasterCms\Providers;

use CoasterCms\Facades\Install;
use CoasterCms\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Schema;

class CoasterConfigProvider extends ServiceProvider
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
     * @return void
     */
    public function boot()
    {
        // overwrite config files with settings in database table, only works post-boot which is normall good enough
        // also doesn't overwrite any data with blanks
        // some settings may have been used in providers register() , if needed to change pre-boot change in files
        $db = false;
        try {
            if (Schema::hasTable('settings')) {
                $db = true;
                Setting::loadAll('coaster');
            }
        } catch (\PDOException $e) {}

        if (Install::isComplete()) {
            if (!$db) {
                abort(503, 'Database error, settings table could not be found');
            }
        }

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // register default config
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/coaster.php'), 'coaster');

        // override auth & croppa
        $overrides = $this->app['config']->get('coaster.overrides', []);
        foreach ($overrides as $key => $value) {
            if (!is_null($value)) {
                $this->app['config']->set($key, $value);
            }
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
