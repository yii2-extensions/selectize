# Configuration and integration

The widget wraps [Selectize 0.15.2](https://github.com/selectize/selectize.js/tree/v0.15.2). Its upstream repository is
archived. The npm release supplies `dist/css/selectize.bootstrap5.css`. The package ships a compatible prebuilt
JavaScript runtime with selector-safe label lookup and collision-safe option and search dictionaries; see its
[source and licenses](../src/asset/js/selectize/NOTICE.md). No JavaScript build is needed. Complete the
[installation and aliases](installation.md) before configuring widgets.

The extension uses [BSD-3-Clause](../LICENSE). The bundled runtime retains its upstream Apache-2.0 and MIT terms in the
notices above.

## Mixed asset installers

Keep an application's existing JavaScript manager when it provides the required assets. If choosing Foxy native while
retaining Composer `npm-asset/*` packages, give native assets their own directory:

```json
{
  "config": {
    "foxy": {
      "manager": "native",
      "native-install-dir": "vendor/selectize-frontend"
    }
  }
}
```

Keep the existing global `@npm` and `@bower` aliases for other bundles. Merge these overrides into the application
configuration, adjusting the dedicated alias to the actual native install path:

```php
use yii2\extensions\selectize\asset\BootstrapAsset;
use yii2\extensions\selectize\asset\SelectizeAsset;
use yii\web\JqueryAsset;

return [
    'aliases' => ['@selectizeNpm' => '@vendor/selectize-frontend'],
    'components' => [
        'assetManager' => [
            'bundles' => [
                SelectizeAsset::class => [
                    'sourcePath' => '@selectizeNpm/@selectize/selectize/dist',
                ],
                BootstrapAsset::class => [
                    'sourcePath' => '@selectizeNpm/bootstrap/dist',
                ],
                JqueryAsset::class => [
                    'sourcePath' => '@selectizeNpm/jquery/dist',
                ],
            ],
        ],
    ],
];
```

The shared `JqueryAsset` uses the native copy in this recipe on both Yii lines; other npm/Bower bundles keep their
original alias paths. Native installation must not overwrite directories owned by Composer or another installer.

## Choose the input and binding

Bind `model` and `attribute`, or use standalone `name` and `value` (inherited from `InputWidget`). `items` maps
submitted values to labels and supports Yii optgroups; text mode ignores it. `options` contains HTML attributes on the
original select or input, such as `id`, `multiple`, `disabled`, `prompt` and ARIA attributes. `clientOptions` passes
Selectize settings and callbacks; functions use `JsExpression`. The default type is `Selectize::TYPE_SELECT`; use
`Selectize::TYPE_TEXT` for a text input. Other values throw. `loadUrl` is a Yii route/parameters array such as
`['tag/search']`; an empty array disables the built-in loader.

`queryParam` defaults to `query`. `loadUrl` overrides `clientOptions['load']` for that widget. Leave `loadUrl` empty to
provide a custom loader. Callback settings belong in `clientOptions`; the widget has no `clientEvents` property.

## Model validation and multiple values

A select with `options['multiple'] = true` submits an array. Set both that HTML attribute and an array-compatible model
rule; `clientOptions['maxItems']` alone does not define the submitted PHP shape. Yii adds the `[]` suffix. Model-bound
fields also get a hidden unselect input; for standalone fields, set `options['unselect'] = ''` to submit a marker when
all selections are cleared. Give each widget its own ID, including when using tabular names such as `[0]tags` and
`[1]tags`.

```php
use yii\base\Model;

final class ArticleForm extends Model
{
    public $tags = [];

    public function rules(): array
    {
        return [
            [
                'tags',
                'filter',
                'filter' => static fn ($value) => $value === '' ? [] : $value,
            ],
            ['tags', 'required'],
            [
                'tags',
                'each',
                'rule' => [
                    'in',
                    'range' => ['php', 'yii'],
                    'skipOnEmpty' => false,
                ],
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return ['tags' => 'Tags'];
    }
}
```

Load and validate on the server before saving: `$model->load($request->post()) && $model->validate()`. Render the same
model after a validation failure to preserve values and errors. Validate remote IDs against records the current user may
access; a result shown in the dropdown is not permission to save that ID. Normalize the hidden empty string to an array
before validation, and reject empty members inside a nonempty array.

Malformed submissions can contain arrays where a string or scalar option ID is expected. Keep the model errors and
provide safe display values through `options['value']` before redisplaying a rejected form.

## Text input

```php
use yii2\extensions\selectize\Selectize;

echo Selectize::widget(
    [
        'name' => 'keywords',
        'value' => 'php,yii',
        'type' => Selectize::TYPE_TEXT,
        'clientOptions' => ['create' => true, 'delimiter' => ','],
    ],
);
```

Text mode submits a delimiter-separated string and ignores PHP `items`. Use `clientOptions['options']` with objects
containing `value` and `text` for available text-mode choices. Validate and split the string explicitly in the
application when persistence expects a list; values containing the delimiter need a different data contract.

## Remote data

```php
use yii2\extensions\selectize\Selectize;

echo Selectize::widget(
    [
        'id' => 'article-topic',
        'name' => 'topic',
        'value' => 'yii',
        'items' => ['yii' => 'Yii'],
        'loadUrl' => ['tag/search'],
        'queryParam' => 'search-term',
        'clientOptions' => [
            'maxItems' => 1,
            'valueField' => 'value',
            'labelField' => 'text',
            'searchField' => ['text'],
            'loadThrottle' => 300,
        ],
    ],
);
```

The built-in loader sends GET with the query string and expects a JSON **array**, not an envelope:

```json
[
  { "value": "yii", "text": "Yii" },
  { "value": "php", "text": "PHP" }
]
```

An empty query makes no request, including `preload: true`. Supply selected IDs and labels in `items` when editing
existing records. For a custom response envelope, empty-query preloading, pagination, additional headers, or another
HTTP method, use a `JsExpression` custom `load` callback and leave `loadUrl` empty. Every custom load must call
`callback(rows)` or `callback()` exactly once, including failures.

The built-in loader aborts the previous request when a new search starts, ignores completions after destruction, and
releases loading state after HTTP/JSON failures. Non-array responses become empty results and can be retried. Failed
query keys are removed from Selectize's search cache so the same query can be retried. Use custom loading to show a
visible error/retry status or to abort immediately when the search text becomes empty; Selectize's empty-query branch
does not start a new request. Successful queries are cached by Selectize until options/search state is cleared.

Production endpoints should apply authentication, authorization, bounded query length and result count, and
parameterized database queries.

## Rendering and encoding

Yii encodes option labels by default. `options['encode'] = false` deliberately permits HTML in the original select
markup; Selectize reads option text, so this setting is not a custom Selectize renderer. Do not classify an intentional
HTML option as an escaping bug merely because the markup is preserved.

Selectize's default renderers escape labels. For custom markup, escape untrusted fields with the renderer's `escape`
argument while keeping the chosen static HTML:

```php
use yii\web\JsExpression;

$clientOptions = [
    'render' => [
        'option' => new JsExpression(
            <<<'JS'
                function (item, escape) {
                    return '<div><strong>' + escape(item.text) + '</strong></div>';
                }
            JS,
        ),
    ],
];
```

`JsExpression` is executable application code. JSON encoding protects surrounding string data; it does not sanitize the
source of a supplied callback. URLs and query parameter names are encoded as JavaScript data.

For event callbacks, runtime validation state and fragment replacement, continue with
[events and lifecycle](lifecycle.md).

[Back to documentation](../README.md#documentation)
