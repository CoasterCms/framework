# Upgrading from Coaster CMS 8 to 13

Coaster 13 runs on Laravel 13 and PHP 8.3+ (tested on 8.4 and 8.5, with MySQL 5.7 and 8.4).
The version follows the Laravel major it targets; there are no Coaster 9 - 12 releases.

13.0 is a compatibility release: it makes Coaster CMS and Coaster Commerce run on current
Laravel and PHP, and fixes a few bugs found along the way. It adds no features.

## Requirements

- PHP 8.3 or newer
- Laravel 13
- `coastercms/framework` and `coastercms/coastercommerce` at `^13.0` (while 13.0 is in beta, `^13.0@beta`)

## Upgrading a site

Upgrade the application itself to Laravel 13 following the Laravel upgrade guides, then:

1. **Composer**
   - Require `coastercms/framework:^13.0` (and `coastercms/coastercommerce:^13.0`).
   - Remove `fideloper/proxy` and `fruitcake/laravel-cors`: extend `Illuminate\Http\Middleware\TrustProxies`
     and use `Illuminate\Http\Middleware\HandleCors` instead. `Request::HEADER_X_FORWARDED_ALL` no longer
     exists; list the `HEADER_X_FORWARDED_*` constants you need.
   - Other versions known to work: `laravel/scout:^11`, `algolia/scout-extended:^5`,
     `algolia/algoliasearch-client-php:^4`, `laravel/tinker:^3`, `laravel/ui:^4.6.3`.

2. **Published `config/coaster.php`** - croppa (image resizing) now reads and writes through
   filesystem disks. Under `'overrides'`, replace:

   ```php
   'croppa.src_dir' => public_path(),
   'croppa.crops_dir' => public_path().'/cache',
   ```

   with:

   ```php
   'filesystems.disks.coaster-public' => ['driver' => 'local', 'root' => public_path()],
   'filesystems.disks.coaster-crops' => ['driver' => 'local', 'root' => public_path('cache'), 'url' => '/cache'],
   'croppa.src_disk' => 'coaster-public',
   'croppa.crops_disk' => 'coaster-crops',
   'croppa.tmp_disk' => 'coaster-crops',
   ```

   Crops are still written to `public/cache/...` and generated image URLs do not change.

3. **Forms** - `laravelcollective/html` is replaced by `laravellux/html`, a maintained fork with the
   same API. `Form::` / `HTML::` and `$formBuilder` keep working; only references to the
   `Collective\Html\...` class names need changing to `LaravelLux\Html\...`.

4. **Your own code and published views**
   - `$request->get()` / `Request::get()` / `request()->get()`: removed in Symfony 8, use `input()`.
   - `$dates` on models: use `datetime` casts. Coaster's own models already do.
   - Mailable callbacks receive `Symfony\Component\Mime\Email`, not `Swift_Message`.
   - Blade (Laravel 12+): a directive glued to a word (`text@else`) is no longer compiled, and
     `@context` is now a directive - escape JSON-LD as `"@@context"`. Compiling every template and
     running `php -l` on the output is a quick way to find these.
   - Subclasses of Coaster models or controllers: some parameters are now explicitly nullable
     (`?Product $product = null`), so overriding signatures must match.

5. Clear `bootstrap/cache/*.php` and compiled views after updating, as the cached provider list
   still names the old croppa and collective providers.

## Fixes in 13.0

- Admin dates (logs, users, search terms) rendered blank: `DateTimeHelper` read a config key
  that does not exist.
- `LoadMiddleware` assigned router middleware to a misspelt property, so listeners could not add
  router middleware.
- Coaster Commerce: see its UPGRADE-13.md.
