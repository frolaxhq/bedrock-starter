# Bedrock Starter

The official Laravel starter application for [Frolax Bedrock](https://github.com/frolaxhq/bedrock).

Create a project from this starter once it is published to Packagist:

```bash
laravel new new-project --using=frolaxhq/bedrock-starter
```

The starter deliberately includes only `frolaxhq/bedrock` core. It gives every project
module lifecycle, settings, permissions, diagnostics, and a clean module directory without
forcing an admin panel, Inertia, Spatie Permission, Node, or business features into projects
that do not need them.

The starter's welcome page uses embedded styles. Its setup runs Composer, Laravel key
generation, and migrations only; it has no default JavaScript build. Add the optional
frontend package and Vite+ host configuration when the application needs React.

## After creation

```bash
php artisan vendor:publish --tag=bedrock-config
php artisan migrate
```

Add only the optional capabilities the project uses:

```bash
# Role and permission adapter
composer require frolaxhq/bedrock-access-spatie

# Blade administration console; includes the Spatie adapter
composer require frolaxhq/bedrock-admin

# Inertia integration, frontend artifacts, and frontend module authoring
composer require frolaxhq/bedrock-frontend
```

Generate a backend module when the project has a self-contained feature:

```bash
php artisan module:make billing
php artisan module:validate modules/billing
php artisan module:install billing
php artisan module:enable billing
```

Each generated module owns `composer.json` and `composer.lock`. Add private SDKs inside the
module directory and commit the lockfile. Host Laravel/Bedrock/Spatie integrations remain
declared in `module.json.composer.require` and installed explicitly in the host.

```bash
cd modules/billing
composer require vendor/sdk:^1.0
cd ../..
php artisan module:prepare billing
php artisan module:pack billing
```

Default ZIPs bundle a scoped PHP runtime, so installing them needs no Composer or internet.
`module:pack --without-php-runtime` creates a locked source ZIP; installation prepares its
private dependencies automatically. `module:install --offline` forbids downloads and
`--queue` schedules installation. Private SDK objects should stay inside their module; use
module-owned DTOs or shared contracts across boundaries.

Admin installation requires an asynchronous queue connection and dedicated worker:

```bash
php artisan queue:work --queue=bedrock-install --timeout=1260 --tries=1
```

Keep the queue connection's `retry_after` above that timeout. Repair database failures
explicitly before retrying; installation jobs do not automatically replay migrations.

Use normal Laravel application code for features that belong to every application. Use a
Bedrock module when a feature should be independently installed, enabled, disabled, packaged,
or reused. See the [Bedrock documentation](https://github.com/frolaxhq/bedrock#readme) for
module manifests, frontend modules, and operations.

## Requirements

- PHP 8.5+
- Laravel 13
- Node 24 LTS only for an Inertia frontend or frontend module authoring

This starter has no Composer path repositories and no committed lockfile, so a new project
resolves published dependencies from Packagist.
