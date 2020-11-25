<p align="center"><img src="https://www.coastercms.org/uploads/images/logo_coaster_github4.jpg"></p>

<p align="center">
  <a href="https://packagist.org/packages/web-feet/coasterframework"><img src="https://poser.pugx.org/web-feet/coasterframework/downloads.svg"></a>
  <a href="https://packagist.org/packages/web-feet/coasterframework"><img src="https://poser.pugx.org/web-feet/coasterframework/version.svg"></a>
  <a href="https://www.gnu.org/licenses/gpl-3.0.en.html"><img src="https://poser.pugx.org/web-feet/coasterframework/license.svg"></a>
</p>

This is the codebase for Coaster CMS - all the inner workings are here and it is designed to work in conjunction with the Coaster CMS framework (https://github.com/Web-Feet/coastercms).

You can also use this as a stand-alone library to add content management functionality to your project.

## Add to an Existing Laravel Project (v8)

The steps are are as follows:

1. Add "web-feet/coasterframework": "~8.0" to the composer.json file and run composer update
2. Go to you config/app.php file
3. Add the service provider CoasterCms\CmsServiceProvider::class (ideally before app providers)
4. Add the service provider CoasterCms\Providers\CoasterRoutesProvider::class (near the end as it registers a catch-all route)
5. Go to the root directory of your project
6. Run the script <code>php artisan coaster:update-assets</code>
7. Manually copy/merge the files in config/publish/ to your root config directory or run script <code>php artisan vendor:publish --force --tag coaster.config</code>
8. Go to a web browser and follow the install script that should have appeared (on [your url]/install)
9. Upload or create a theme
