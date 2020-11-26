<p align="center"><img src="https://www.coastercms.org/uploads/images/logo_coaster_github4.jpg"></p>

<p align="center">
  <a href="https://packagist.org/packages/coastercms/framework"><img src="https://poser.pugx.org/coastercms/framework/downloads.svg"></a>
  <a href="https://packagist.org/packages/coastercms/framework"><img src="https://poser.pugx.org/coastercms/framework/version.svg"></a>
  <a href="https://www.gnu.org/licenses/gpl-3.0.en.html"><img src="https://poser.pugx.org/coastercms/framework/license.svg"></a>
</p>

This is the codebase for Coaster CMS - all the inner workings are here and it is designed to work in conjunction with the Coaster CMS framework (https://github.com/Web-Feet/coastercms).

You can also use this as a stand-alone library to add content management functionality to your project.

## Add to an Existing Laravel Project (v8)

The steps are are as follows:

1. Go to the root directory of your project
2. Run <code>composer require coastercms/framework:~8.0</code> to install package
3. Run <code>php artisan coaster:update-assets</code> to download admin assets
4. Add the provider CoasterCms\Providers\CoasterRoutesProvider::class to your config/app.php file (near end as it registers a catch-all route)
5. Go to a web browser and follow the install script that should have appeared
6. Upload or create a theme
