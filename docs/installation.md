# Installation guide

## Requirements

Use PHP 8.3 or later, Composer 2.10.2 or later and Yii `~2.0.55 || ^22.0@dev`. Foxy 0.4 requires this Composer version
with every manager. The native manager also needs PHP `zlib`; it installs published files without Node.js or lifecycle
scripts. The package supplies a prebuilt Selectize runtime and Bootstrap 5 theme assets, so there is no JavaScript build
step.

Preserve an application's existing asset manager when it supplies the required packages. The configuration below selects
Foxy native for an application whose `vendor/npm-asset` directory is not owned by another installer. If Composer also
installs `npm-asset/*` packages, use a separate native directory and the
[mixed-installer bundle overrides](configuration.md#mixed-asset-installers).

## Configure the application

Merge these settings into the application's root `composer.json` before requiring the extension; dependency
configuration is not inherited:

```json
{
  "config": {
    "allow-plugins": {
      "php-forge/foxy": true,
      "yiisoft/yii2-composer": true
    },
    "foxy": {
      "manager": "native",
      "native-install-dir": "vendor/npm-asset"
    }
  },
  "repositories": [{ "type": "composer", "url": "https://asset-packagist.org" }]
}
```

The asset repository supplies Yii 2.0's Bower dependencies. Yii 22 uses npm jQuery and does not need that repository.
Keep `@bower` for other application bundles if they still use it.

Install with Composer:

```shell
composer require --prefer-dist yii2-extensions/selectize:dev-main
```

To select Yii 22, explicitly allow its development version in the root app:

```shell
composer require --prefer-dist yii2-extensions/selectize:dev-main "yiisoft/yii2:^22.0@dev"
```

For the native directory above, merge the asset aliases into the Yii application configuration:

```php
'aliases' => [
    '@npm' => '@vendor/npm-asset',
    '@bower' => '@vendor/bower-asset', // Yii 2.0 Bower dependencies
],
```

Resolve those aliases from the actual Composer vendor directory. Do not move a global alias that other bundles need: use
the dedicated alias and bundle overrides in the mixed-installer recipe instead.

## Verify the integration

Render the [basic widget](../README.md#basic-usage) in a view with a visible label. Check the page's network requests
for Bootstrap CSS/JS, jQuery, the Selectize theme, the bundled `selectize/selectize.js` runtime and `selectize.yii.js`.
Inspect the original input's `selectize` property to confirm initialization. An asset-manager path exception usually
means an alias or installation directory does not match the chosen manager.

Continue with [configuration and forms](configuration.md), the [usage examples](examples.md) or the
[upgrade guide](../UPGRADE.md).

[Back to documentation](../README.md#documentation)
