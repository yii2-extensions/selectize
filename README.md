<p align="center">
    <a href="https://github.com/yii2-extensions/selectize" target="_blank">
        <img src="https://www.yiiframework.com/image/yii_logo_light.svg" height="100px;">
    </a>
    <h1 align="center">Selectize.</h1>
    <br>
</p>

<p align="center">
    <a href="https://www.php.net/releases/8.1/en.php" target="_blank">
        <img src="https://img.shields.io/badge/PHP-%3E%3D8.1-787CB5" alt="php-version">
    </a>
    <a href="https://github.com/yii2-extensions/selectize/actions/workflows/build.yml" target="_blank">
        <img src="https://github.com/yii2-extensions/selectize/actions/workflows/build.yml/badge.svg" alt="PHPUnit">
    </a>
    <a href="https://github.com/yii2-extensions/selectize/actions/workflows/compatibility.yml" target="_blank">
        <img src="https://github.com/yii2-extensions/selectize/actions/workflows/compatibility.yml/badge.svg" alt="PHPUnit">
    </a>        
    <a href="https://codecov.io/gh/yii2-extensions/selectize" target="_blank">
        <img src="https://codecov.io/gh/yii2-extensions/selectize/branch/main/graph/badge.svg?token=MF0XUGVLYC" alt="Codecov">
    </a>
    <a href="https://dashboard.stryker-mutator.io/reports/github.com/yii2-extensions/selectize/main" target="_blank">
        <img src="https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyii2-extensions%2Fselectize%2Fmain" alt="Infection">
    </a>          
</p>

## Installation

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```shell
composer require --prefer-dist yii2-extensions/selectize:^0.1
```

or add

```json
"yii2-extensions/selectize": "^0.1"
```

to the require section of your `composer.json` file.

## Usage

### Dropdown list 

```php
use Yii2\Extension\Selectize\Selectize;

Selectize::widget(
    [
        'attribute' => 'tags',
        'items' => ['foo', 'bar'],
        'model' => new SelectizeModel(), // your model
    ],
);
```

### Text input

```php
use Yii2\Extension\Selectize\Selectize;

Selectize::widget(
    [
        'attribute' => 'tags',
        'items' => ['foo', 'bar'],
        'model' => new SelectizeModel(), // your model
        'type' => Selectize::TYPE_TEXT, // `Selectize::TYPE_SELECT`, `Selectize::TYPE_TEXT`
    ],
);
```

### Properties of the widget

| Property       | Type          | Description                                                                      | Default                  |
|----------------|---------------|----------------------------------------------------------------------------------|--------------------------|
| `attribute`    | `string`      | The attribute associated with this widget.                                       | `null`                   |
| `clientOptions`| `array`       | The options for the underlying Selectize JS plugin.                              | `[]`                     |
| `items`        | `array`       | Items to be displayed in the dropdown list.                                      | `[]`                     |
| `loadUrl`      | `string`      | The URL that will return JSON data.                                              | `null`                   |
| `model`        | `Model`       | The data model that this widget is associated with.                              | `null`                   |
| `options`      | `array`       | The HTML attributes for the widget container tag.                                | `[]`                     |
| `queryParam`   | `string`      | The name of the parameter that will be sent to the server with the search query. | `query`                  |
| `type`         | `string`      | The type of the widget.                                                          | `Selectize::TYPE_SELECT` |

## Quality code

[![static-analysis](https://github.com/yii2-extensions/selectize/actions/workflows/static.yml/badge.svg)](https://github.com/yii2-extensions/selectize/actions/workflows/static.yml)
[![phpstan-level](https://img.shields.io/badge/PHPStan%20level-7-blue)](https://github.com/yii2-extensions/selectize/actions/workflows/static.yml)
[![StyleCI](https://github.styleci.io/repos/720718108/shield?branch=main)](https://github.styleci.io/repos/720718108?branch=main)

## Support versions Yii2

[![Yii20](https://img.shields.io/badge/Yii2%20version-2.0-blue)](https://github.com/yiisoft/yii2/tree/2.0.49.3)
[![Yii22](https://img.shields.io/badge/Yii2%20version-2.2-blue)](https://github.com/yiisoft/yii2/tree/2.2)

## Testing

[Check the documentation testing](/docs/testing.md) to learn about testing.

## Our social networks

[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/Terabytesoftw)

## License

The MIT License. Please see [License File](LICENSE) for more information.
