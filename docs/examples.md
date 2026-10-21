# Usage examples

These recipes run in an application view and controller. Validate every submitted value on the server as shown in the
[configuration guide](configuration.md#model-validation-and-multiple-values).

## Model rules

```php
use yii\base\Model;

final class PostForm extends Model
{
    public $category = '';
    public $tags = [];
    public $keywords = '';

    public function rules(): array
    {
        return [
            ['category', 'required'],
            ['category', 'in', 'range' => ['php', 'yii']],
            ['tags', 'filter', 'filter' => static fn ($value) => $value === '' ? [] : $value],
            ['tags', 'each', 'rule' => ['in', 'range' => ['php', 'yii', 'js'], 'skipOnEmpty' => false]],
            ['keywords', 'string', 'max' => 80],
        ];
    }
}
```

## Single choice with validation

Redisplay only a scalar value after a rejected submission, and connect the help and error elements to the field.

```php
use yii2\extensions\selectize\Selectize;

echo $form->field($model, 'category', ['errorOptions' => ['id' => 'category-error']])->widget(Selectize::class, [
    'items' => ['php' => 'PHP', 'yii' => 'Yii'],
    'options' => [
        'prompt' => 'Choose a category',
        'value' => is_string($model->category) ? $model->category : '',
        'aria-describedby' => 'category-help category-error',
        'aria-invalid' => $model->hasErrors('category') ? 'true' : 'false',
    ],
    'clientOptions' => ['maxItems' => 1],
]);
```

## Multiple values

```php
use yii2\extensions\selectize\Selectize;

echo $form->field($model, 'tags')->widget(Selectize::class, [
    'items' => ['php' => 'PHP', 'yii' => 'Yii', 'js' => 'JavaScript'],
    'options' => [
        'multiple' => true,
        'value' => is_array($model->tags) ? array_values(array_filter($model->tags, 'is_string')) : [],
    ],
    'clientOptions' => ['plugins' => ['remove_button']],
]);
```

## Free-text keywords

A text input submits one comma-separated string and ignores `items`.

```php
use yii2\extensions\selectize\Selectize;

echo $form->field($model, 'keywords')->widget(Selectize::class, [
    'type' => Selectize::TYPE_TEXT,
    'options' => ['value' => is_string($model->keywords) ? $model->keywords : ''],
    'clientOptions' => ['create' => true, 'delimiter' => ','],
]);
```

## Remote search

Supply the preselected value and its label in `items`; the widget loads further options from the endpoint.

```php
use yii\helpers\Html;
use yii2\extensions\selectize\Selectize;

echo Html::label('Remote tag', 'remote-tag');
echo Selectize::widget([
    'id' => 'remote-tag',
    'name' => 'remote',
    'value' => 'php',
    'items' => ['php' => 'PHP'],
    'loadUrl' => ['post/tags'],
    'queryParam' => 'search-term',
    'clientOptions' => ['maxItems' => 1],
]);
```

The endpoint returns a JSON array of `value`/`text` rows and rejects malformed or oversized queries. Add the
application's authentication and authorization, and query the database with bound parameters.

```php
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

final class PostController extends Controller
{
    public function behaviors(): array
    {
        return ['verbs' => ['class' => VerbFilter::class, 'actions' => ['tags' => ['GET']]]];
    }

    public function actionTags(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $query = Yii::$app->request->get('search-term', '');

        if (!is_string($query) || mb_strlen($query) > 80) {
            Yii::$app->response->statusCode = 400;

            return [];
        }

        $rows = [];

        foreach (['php' => 'PHP', 'yii' => 'Yii', 'js' => 'JavaScript'] as $value => $text) {
            if ($query !== '' && stripos($text, $query) !== false) {
                $rows[] = ['value' => $value, 'text' => $text];
            }
        }

        return $rows;
    }
}
```

## Clear and reinitialize

Give touch users an accessible way to clear a single selection, and reinitialize through `yiiSelectize()` after
replacing markup so the widget keeps its accessible attributes. See [events and lifecycle](lifecycle.md) for fragment
replacement and custom handlers.

```js
document.getElementById("clear-remote").addEventListener("click", function () {
  var control = document.getElementById("remote-tag").selectize;
  control.clear();
  control.focus();
});

document.getElementById("reinitialize").addEventListener("click", function () {
  var element = document.getElementById("remote-tag");
  var control = element.selectize;
  var value = control.getValue();
  var options = Object.assign({}, control.settings, {
    options: Object.values(control.options),
    items: control.items.slice(),
    render: control.settings_user.render,
  });
  control.destroy();
  jQuery(element).yiiSelectize(options);
  element.selectize.setValue(value);
});
```

[Back to documentation](../README.md#documentation)
