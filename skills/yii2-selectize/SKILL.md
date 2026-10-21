---
name: yii2-selectize
description:
  Integrate yii2-extensions/selectize with Yii models, forms, remote JSON data
  and repeatable widget lifecycles.
---

# Selectize integration

## Read the relevant contract

In a root package checkout, use `composer show --self --format=json` for metadata. For installed dependencies, resolve
the real path and read the configured vendor directory's `composer/installed.php` (`versions[<package>]`):
`install_path`, `pretty_version` and Composer's selected `reference`. Do not blindly prefer `source.reference` over
`dist.reference`. For any live package checkout, including a symlinked path install, inspect its current `HEAD` and
files even when clean; report metadata mismatches and let the actual files govern. Uncommitted changes have no matching
SHA. Otherwise use the selected reference or a verified matching tag only after establishing its source provenance.
Compare `composer.lock`; it may differ from the inspected installation. Identify a lock-only state when no package files
are installed, and resolve dependencies separately. Use hosted pages, catalogs or snapshots only when their source
revision matches.

Resolve the package root from that installation's real `install_path` or the identified package checkout. Read its
`composer.json`, `package.json`, `docs/` and `src/` before applying these contracts. All package paths here are relative
to that root, including when this skill was copied into a consuming app. Inspect the app's own Composer configuration
separately for its vendor path, manager and plugin settings. If package guides were omitted, read `docs/` at the
resolved reference in <https://github.com/yii2-extensions/selectize>. If unavailable or offline, inspect source/PHPDoc
and the changelog in the resolved package root, and report the gap.

## Dependencies and assets

Use `yii2\extensions\selectize\Selectize`. This revision requires PHP 8.3+, Yii `~2.0.55 || ^22.0@dev` and
`php-forge/foxy:^0.4`. Foxy 0.4 requires Composer `^2.10.2` for every manager. Authorize `php-forge/foxy` and
`yiisoft/yii2-composer` in the root application's `config.allow-plugins`; dependency configuration is not inherited.

Preserve the application's chosen JavaScript manager and dependency layout. For published assets without a JavaScript
build, the root app can select `config.foxy.manager = native` with `config.foxy.native-install-dir` set to a dedicated
directory such as `vendor/npm-asset`. Native mode needs `zlib` and does not run lifecycle scripts. Do not share its
directory with another installer. `SelectizeAsset` publishes the Selectize 0.15.2 stylesheet from
`@npm/@selectize/selectize/dist`. Its `SelectizeRuntimeAsset` dependency loads the package's prebuilt runtime from
`src/asset/js/selectize/selectize.js`, with fixes for IDs and option/search keys; see
`src/asset/js/selectize/NOTICE.md`. No JavaScript build is needed. `package.json` also declares Bootstrap and jQuery.

When the selected manager supplies the app's `@npm` assets, set that alias to its actual install directory
(`@vendor/npm-asset` for the native directory in the installation guide). Yii 2.0.55's `JqueryAsset` defaults to
`@bower/jquery/dist`; its Bower dependencies need asset-packagist and normally `@bower` set to `@vendor/bower-asset`.
Yii 22's `JqueryAsset` defaults to `@npm/jquery/dist` and does not need that Bower repository. Resolve aliases
explicitly from the consuming app's layout; framework defaults differ between branches.

With retained Composer `npm-asset/*` packages or legacy bundles using `@npm`, preserve the existing `@npm` and `@bower`
aliases. If using Foxy native, choose an exclusive directory such as `vendor/selectize-frontend`, add `@selectizeNpm`
for it, and configure `assetManager.bundles` `sourcePath` overrides for this package's `SelectizeAsset` and
`BootstrapAsset`, and `yii\web\JqueryAsset`. Follow `docs/configuration.md#mixed-asset-installers` in the resolved
package root for the bundle paths; retain the application's chosen manager.

## Forms and values

- Supply `model` and `attribute`, or standalone `name`/`value`. Render with `echo` or an ActiveField `widget()` call.
- `TYPE_SELECT` is the default. `items` maps submitted values to labels and supports Yii optgroups. Text mode uses
  `TYPE_TEXT`, ignores PHP `items`, and submits a delimiter-separated string. Supply text-mode choices through
  `clientOptions['options']` as `{value, text}` records. Validate and split text explicitly if persistence expects a
  list.
- Multiple selects need `options['multiple'] = true`, array values and an array-compatible model validator; `maxItems`
  alone does not define the submitted PHP shape. Normalize only a cleared select's hidden `''` value to `[]` before
  validation. Validate the outer array and every allowed scalar ID; configure embedded validators with
  `skipOnEmpty => false` when empty IDs are invalid, including `['']`, `[null]` and `[[]]`. Load and validate the model
  before persistence and authorize submitted IDs on the server.
- Keep the model and its errors after rejected submissions. Malformed values can still break HTML rendering: set
  `options['value']` to a scalar or `''` for model-bound single/text controls. For multiple controls, keep only scalar
  array members for display and use `[]` for a non-array submission. Build that display value separately, preserving
  rejected model values and errors; it is not validated data for persistence.
- Give every instance a unique ID and visible label. Connect help/errors through `aria-describedby`, keep
  `ActiveField::$addAriaAttributes` enabled, and set validation state on the original input's `aria-invalid`; the
  wrapper observes that attribute. Use `control.enable()`/`disable()` for runtime disabled-state changes.
- Yii 22 defaults `ActiveForm::$clientScript` to `null`. When client validation is required, configure an
  application-selected form and validator strategy. The optional `yiisoft/yii2-jquery` integration supplies these when
  `yii\jquery\Bootstrap::class` is added to the application's `bootstrap`. Resolve its compatible installed/available
  version before adding it. Selectize and server validation do not require it.

## Remote data and rendering

- `loadUrl` is a Yii route **array**, default `[]`; `queryParam` defaults to `query`. The built-in GET endpoint returns
  a JSON array of `{value, text}` rows with the default field settings. Prepopulate selected IDs and labels in `items`
  for select mode. `loadUrl` overrides `clientOptions['load']`.
- Empty queries skip requests even with `preload: true`. Use a custom `load` and leave `loadUrl` empty for response
  envelopes, empty-query preloading, pagination, extra headers, another HTTP method, or immediate cancellation when
  search text is cleared. Built-in failures release loading state and allow the same query to be retried; successful
  searches are cached. Add explicit status UI when users need visible error/retry feedback.
- A selected single-value control blocks typing. Clear it with Backspace or `control.clear()` before searching; provide
  an accessible clear action for touch users.
- Configure callbacks (`onChange`, `onItemAdd`, `load`, render functions) under `clientOptions` as
  `yii\web\JsExpression`. There is no `clientEvents` property. Preserve the Selectize `this` instance when needed, and
  settle custom load callbacks exactly once on success and failure.
- Yii option labels and Selectize's default labels are encoded. `options['encode'] = false` intentionally permits HTML
  in the original options. Custom renderers keep static markup and escape untrusted fields through their `escape`
  argument. `JsExpression` contains executable application code; encoding surrounding data does not sanitize a callback.

## Events and lifecycle

Initialize with `jQuery(element).yiiSelectize(settings)` and access methods through `element.selectize`. Repeated calls
on a live instance are no-ops and do not apply new settings. `setValue(value)` emits change for form integration;
`setValue(value, true)` suppresses it. Remove your event handler with `control.off(event, handler)`; argumentless
`off()` removes integration handlers as well.

Before fragment removal, call `destroy()` on each instance; cleanup aborts built-in loads, disconnects observers and
restores label targets. Remove your own external listeners separately. For PJAX, destroy in `pjax:beforeReplace` and
initialize after replacement, scoped to that container. Save current options and selection before destroying when
reinitialization must preserve edits; upstream destruction restores the original option nodes.

## Verification

Use the consuming application's existing checks and exercise the affected form flow: invalid submission, cleared
multiple values, malformed scalar/nested values, remote success/failure/retry, repeated destroy/reinitialize,
independent widgets, keyboard selection and a narrow touch viewport as applicable. Check that rejected submissions
render their errors without conversion warnings. Record the Yii/PHP/browser versions actually tested and remaining gaps.
