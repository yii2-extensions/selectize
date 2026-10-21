# Upgrade Guide

## 0.2.0

- Upgrade PHP to 8.3 or later and Yii to `~2.0.55 || ^22.0@dev`.
- Authorize `php-forge/foxy` in the root application and configure its asset manager as shown in the
  [installation guide](docs/installation.md). If Composer-managed `npm-asset/*` packages remain, keep their `@npm` alias
  and use a dedicated native directory with the [bundle overrides](docs/configuration.md#mixed-asset-installers).
- Replace asset overrides pointing to `@npm/selectize--selectize/dist` with `@npm/@selectize/selectize/dist`.
- Update imports from `Yii2\Extensions\Selectize` to `yii2\extensions\selectize`, and asset bundle overrides to
  `yii2\extensions\selectize\asset`.
- Move overrides of `Yii2\Asset\BootstrapAsset` or `BootstrapPluginAsset` to
  `yii2\extensions\selectize\asset\BootstrapAsset`. Applications that use the former bundles elsewhere must require
  `yii2-extensions/asset-bootstrap5` themselves.
- Move a `js` override of `SelectizeAsset` to `yii2\extensions\selectize\asset\SelectizeRuntimeAsset`.
- Use `jQuery(element).yiiSelectize(settings)` to initialize or reinitialize rendered fields manually.
- In custom Selectize plugins, use `Object.hasOwn(map, key)` for `options`, `optgroups`, `userOptions`, `renderCache`
  and `loadedSearches`; these maps no longer have a prototype.
- Supply JSON objects in option `data-data` metadata. Submitted identifiers always come from the HTML option value.
