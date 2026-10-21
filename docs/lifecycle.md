# Events, accessibility and lifecycle

## Events

Configure native callbacks with `JsExpression`, preserving the Selectize `this` instance:

```php
use yii\web\JsExpression;

$clientOptions = [
    'onChange' => new JsExpression('function (value) { console.log(value); }'),
    'onItemAdd' => new JsExpression('function (value) { console.log(value); }'),
];
```

Or subscribe after initialization: `control.on('change', handler)` and unsubscribe with
`control.off('change', handler)`. Do not call `off()` without arguments on an active widget; that also removes its
integration handlers. jQuery `change` on the original element continues to support Yii ActiveForm. `setValue(value)`
triggers change; `setValue(value, true)` suppresses it. Multiple selects return arrays from `getValue()`; single selects
and text inputs return strings.

## Accessibility and validation

Use a visible `<label for="input-id">`, unique IDs and descriptive help/error elements. The integration preserves
Selectize's label association with `input-id-selectized`, copies `aria-label`, `aria-labelledby`, `aria-describedby` and
`title` to the visible input, and adds combobox/listbox roles, expanded state and active-option references. It mirrors
required/disabled state and observes `aria-invalid` on the original field. Label targets are restored on `destroy()`, so
reinitialization retains the association.

Connect server errors through `aria-invalid` and `aria-describedby`. Keep `ActiveField::$addAriaAttributes` enabled (the
default). Yii 2.0's jQuery ActiveForm client updates the original input's `aria-invalid`; the wrapper observes that
attribute and does not infer errors from CSS classes. Custom clients can set the same attribute in
`afterValidateAttribute`.

Yii 22 defaults `ActiveForm::$clientScript` to `null`. Client validation needs an application-selected strategy,
including validator strategies. For the official
[jQuery integration](https://github.com/yiisoft/yii2-jquery/tree/264327a894e6e730a43582f653993449b0c0c9e9), install its
available development branch and bootstrap it in the application:

```shell
composer require yiisoft/yii2-jquery:dev-master
```

```php
'bootstrap' => [\yii\jquery\Bootstrap::class],
```

That optional Yii 22 package is not needed for Selectize itself or server validation. Test keyboard and screen-reader
interaction in the consuming form, including any custom templates and plugins.

A selected single-value control is full in Selectize 0.15.2 and blocks typing. Clear its selection with Backspace or
`control.clear()` before searching; provide an accessible clear button for touch users.

Selectize supports typing, arrow navigation, Enter, Escape and Tab. Check touch targets and focus at your application's
mobile breakpoint. A placeholder is not a label. Use `control.disable()` and `control.enable()` to change disabled state
after initialization; changing only the original field does not synchronize Selectize.

## Cleanup and repeated initialization

The PHP widget registers `jQuery(document.getElementById(id)).yiiSelectize(settings)` on DOM ready. Calling
`yiiSelectize` again on an existing instance is a no-op. It does not apply new settings to that instance. Use the same
helper after replacing a fragment. Before removing a fragment, destroy each live instance:

```js
fragment.querySelectorAll("select, input").forEach(function (element) {
  if (element.selectize) {
    element.selectize.destroy();
  }
});
```

Destruction removes Selectize handlers and dropdowns, disconnects the ARIA observer and aborts the built-in remote
request. Remove your own external listeners separately. For PJAX, destroy in `pjax:beforeReplace` and initialize after
replacement; scope selectors to the container. Do not initialize every input on the page.

To reinitialize the same element while preserving edited values and loaded options:

```js
var element = document.getElementById("article-topic");
var control = element.selectize;
var value = control.getValue();
var settings = Object.assign({}, control.settings, {
  options: Object.values(control.options),
  items: control.items.slice(),
  render: control.settings_user.render,
});
control.destroy();
jQuery(element).yiiSelectize(settings);
element.selectize.setValue(value);
```

Upstream destruction restores the original option nodes, so save the current options and selection first when those
should survive. Use the original `settings_user.render` templates so plugins such as `remove_button` do not wrap
already-modified renderers again. `clear()` clears the selection; `clearOptions()` also removes available choices and
resets search state. Neither destroys the widget. Keep IDs unique across independently configured widgets and tabular
fields; an ID containing a colon is supported by the wrapper without a CSS selector.

See the [native API](https://selectize.dev/docs/API/selectize) for additional methods and
[configuration guide](configuration.md) for loading and server-side data shapes.

[Back to documentation](../README.md#documentation)
