# QR Code module

Generate QR codes from UTF-8 text or URLs at `/qr-code`, or download an SVG at `/qr-code/svg?text=Hello`.

This public demo intentionally has no authentication. Add your host's authentication and authorization middleware before using it for a restricted application. It stores no submitted text and makes no remote requests.

## Dependencies

- Private: `bacon/bacon-qr-code` and its transitive dependency `dasprid/enum`, locked in this module's `composer.lock`.
- Platform: PHP 8.5 and XMLWriter.
- Shared host: Laravel 13 and Bedrock. Laravel is not bundled or scoped.

The host's Composer files and vendor are not modified by module preparation or installation. The page displays the actual prefixed Writer class as visible evidence of isolation. A prefixed namespace isolates dependency versions; it is not a security sandbox for untrusted module code.

## Author and package

From the module directory, install the locked authoring dependencies:

```sh
composer install --no-dev --no-scripts --no-plugins
```

From the host root:

```sh
php artisan module:prepare qr-code
php artisan test modules/qr-code/tests
php artisan module:pack qr-code --output=storage/app/module-artifacts/ready
php artisan module:pack qr-code --without-php-runtime --output=storage/app/module-artifacts/source
```

After changing source files, rebuild using `module:prepare qr-code --force` before packaging. Commit both Composer files; only the author updates dependency versions. The installer never runs `composer update`.

## Install the ZIP in another compatible host

```sh
php artisan module:install qr-code --zip=storage/app/module-artifacts/ready/qr-code-1.0.0.zip --offline
php artisan module:enable qr-code
php artisan module:doctor qr-code
```

The ready ZIP includes a verified `dist/php` runtime, not raw `vendor`; offline installation requires neither dependency downloads nor a scoping build. The source-only ZIP includes the manifest and lockfile; install it without `--offline` to download locked dependencies and prepare the runtime on the destination host.

Installation and activation are separate actions. `module:disable qr-code` makes the module's routes unavailable. Activation/deactivation in production also requires rebuilding deployment caches and restarting long-running workers.
