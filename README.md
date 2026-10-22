<p align="center">
    <a href="https://github.com/yii2-extensions/selectize" target="_blank">
        <img src="https://www.yiiframework.com/image/yii_logo_light.svg" height="100px;" alt="Yii logo">
    </a>
    <h1 align="center">Selectize.</h1>
    <br>
</p>

<p align="center">
    <a href="https://github.com/yii2-extensions/selectize/actions/workflows/build.yml" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/yii2-extensions/selectize/build.yml?style=for-the-badge&label=PHPUnit&logo=github" alt="PHPUnit">
    </a>
    <a href="https://dashboard.stryker-mutator.io/reports/github.com/yii2-extensions/selectize/main" target="_blank">
        <img src="https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyii2-extensions%2Fselectize%2Fmain" alt="Mutation Testing">
    </a>
    <a href="https://github.com/yii2-extensions/selectize/actions/workflows/static.yml" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/yii2-extensions/selectize/static.yml?style=for-the-badge&label=PHPStan&logo=github" alt="PHPStan">
    </a>
</p>

Selectize selects and text inputs for Yii models and forms, with Bootstrap 5 styling, multiple values, remote JSON
choices and lifecycle cleanup.

## Quick start

### Installation

Configure the consuming application's plugins and asset aliases with the [installation guide](docs/installation.md),
then install the documented branch:

```shell
composer require --prefer-dist yii2-extensions/selectize:^0.2
```

The native asset manager installs the published frontend assets without Node.js or a JavaScript build. Keep an existing
application manager when it already supplies those assets.

### Basic usage

Place this in a Yii view and associate the input with a visible label:

```php
use yii\helpers\Html;
use yii2\extensions\selectize\Selectize;

echo Html::label('Category', 'category');
echo Selectize::widget(
    [
        'name' => 'category',
        'value' => 'yii',
        'items' => ['php' => 'PHP', 'yii' => 'Yii'],
        'options' => ['id' => 'category', 'prompt' => 'Choose a category'],
        'clientOptions' => ['maxItems' => 1],
    ],
);
```

Item keys are submitted values. To bind a model attribute in an ActiveForm:

```php
use yii2\extensions\selectize\Selectize;

echo $form->field($model, 'tags')
    ->widget(
        Selectize::class,
        [
            'items' => ['php' => 'PHP', 'yii' => 'Yii'],
            'options' => ['multiple' => true],
            'clientOptions' => ['plugins' => ['remove_button']],
        ],
    );
```

The model must validate `tags` as an array; browser options do not authorize submitted values. See
[model validation](docs/configuration.md#model-validation-and-multiple-values) for cleared fields and rejected
submissions.

## Documentation

Practical guides and examples for widget configuration, forms and remote choices.

- 📚 [Installation Guide](docs/installation.md): packages, managers and asset aliases.
- ⚙️ [Configuration Guide](docs/configuration.md): model values, remote data and rendering.
- 💡 [Usage Examples](docs/examples.md): validated, multiple, text and remote fields.
- 🔁 [Events, Accessibility and Lifecycle](docs/lifecycle.md)
- 🔄 [Upgrade Guide](UPGRADE.md)
- 🧪 [Testing Guide](docs/testing.md)

The guides and the [API reference](https://yii2-framework.github.io/docs/extensions/selectize/reference/) generated from
the PHPDoc in `src/` are published at <https://yii2-framework.github.io/docs/extensions/selectize/>.

## AI assistance

AI agents: read the [documentation index](https://yii2-framework.github.io/docs/llms.txt) and fetch the Markdown version
of a page by appending `index.md` to its URL. The
[AI agents guide](https://yii2-framework.github.io/docs/project/ai-agents/) covers skill installation, client setup and
verification.

- 🤖 [`yii2-selectize` skill](docs/agents.md): Selectize widgets.
- 🧭 [`yii2-framework` skill](https://yii2-framework.github.io/docs/guide/skills/yii2-framework/): Yii 22.0 application
  conventions.

The hosted documentation covers Yii 22.0. If it does not match the installed revision or is unavailable, use the
[repository guides](#documentation), the [skill source](skills/yii2-selectize/SKILL.md), the source PHPDoc and the
[changelog](CHANGELOG.md).

## Package information

[![PHP](https://img.shields.io/badge/%3E%3D8.3-777BB4.svg?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/releases/8.3/en.php)
[![Yii 2.0.55+](https://img.shields.io/badge/%3E%3D2.0.55-0073AA.svg?style=for-the-badge&logo=yii&logoColor=white)](https://github.com/yiisoft/yii2/releases/tag/2.0.55)
[![Yii 22.0.x](https://img.shields.io/badge/22.0.x-0073AA.svg?style=for-the-badge&logo=yii&logoColor=white)](https://github.com/yiisoft/yii2/tree/22.0)
[![Latest Stable Version](https://img.shields.io/packagist/v/yii2-extensions/selectize.svg?style=for-the-badge&logo=packagist&logoColor=white&label=Stable)](https://packagist.org/packages/yii2-extensions/selectize)
[![Total Downloads](https://img.shields.io/packagist/dt/yii2-extensions/selectize.svg?style=for-the-badge&logo=composer&logoColor=white&label=Downloads)](https://packagist.org/packages/yii2-extensions/selectize)

## Project status

[![Codecov](https://img.shields.io/codecov/c/github/yii2-extensions/selectize.svg?style=for-the-badge&logo=codecov&logoColor=white&label=Coverage)](https://codecov.io/github/yii2-extensions/selectize)
[![PHPStan Level 7](https://img.shields.io/badge/PHPStan-Level%207-4F5D95.svg?style=for-the-badge&logo=github&logoColor=white)](https://github.com/yii2-extensions/selectize/actions/workflows/static.yml)
[![StyleCI](https://img.shields.io/badge/StyleCI-Passed-44CC11.svg?style=for-the-badge&logo=github&logoColor=white)](https://github.styleci.io/repos/720718108?branch=main)

## Our social networks

[![Follow on X](https://img.shields.io/badge/-Follow%20on%20X-1DA1F2.svg?style=for-the-badge&logo=x&logoColor=white&labelColor=000000)](https://x.com/Terabytesoftw)
[![Follow on Facebook](https://img.shields.io/badge/-Follow%20on%20Facebook-1877F2.svg?style=for-the-badge&logo=facebook&logoColor=white&labelColor=000000)](https://www.facebook.com/wilmer.arambula.9)
[![Join our Subreddit](https://img.shields.io/badge/-Join%20our%20Subreddit-FF4500.svg?style=for-the-badge&logo=reddit&logoColor=white&labelColor=000000)](https://www.reddit.com/r/Yii2/)
[![Join on Telegram](https://img.shields.io/badge/-Join%20on%20Telegram-26A5E4.svg?style=for-the-badge&logo=telegram&logoColor=white&labelColor=000000)](https://t.me/yii_framework_in_english)

## License

[![License](https://img.shields.io/badge/License-BSD--3--Clause-brightgreen.svg?style=for-the-badge&logo=opensourceinitiative&logoColor=white&labelColor=555555)](LICENSE)

The extension uses [BSD-3-Clause](LICENSE). Bundled third-party JavaScript retains its Apache-2.0 and MIT terms; see the
[third-party runtime notices](src/asset/js/selectize/NOTICE.md).
