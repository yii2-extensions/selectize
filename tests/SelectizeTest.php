<?php

declare(strict_types=1);

namespace Yii2\Extensions\Selectize\Tests;

use PHPForge\Support\Assert;
use Yii;
use Yii2\Extensions\Selectize\Selectize;
use Yii2\Extensions\Selectize\Tests\Support\SelectizeModel;

final class SelectizeTest extends TestCase
{
    public function setup(): void
    {
        parent::setUp();
        $this->mockApplication();

        Selectize::$counter = 0;

        $this->view = Yii::$app->getView();
    }

    public function testId(): void
    {
        $selectize = Selectize::widget(
            [
                'name' => 'tags',
                'id' => 'tests-id',
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            <<<HTML
            <select id="tests-id" name="tags">

            </select>
            HTML,
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#tests-id').selectize([]);
            });</script>
            JS,
            $render,
        );
    }

    public function testIdWithTypeTextInput(): void
    {
        $selectize = Selectize::widget(
            [
                'name' => 'tags',
                'id' => 'tests-id',
                'type' => Selectize::TYPE_TEXT,
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE('<input type="text" id="tests-id" name="tags">', $selectize);

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#tests-id').selectize([]);
            });</script>
            JS,
            $render,
        );
    }

    public function testItems(): void
    {
        $selectize = Selectize::widget(
            [
                'attribute' => 'tags',
                'items' => ['foo', 'bar'],
                'model' => new SelectizeModel(),
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            <<<HTML
            <select id="selectizemodel-tags" name="SelectizeModel[tags]">
            <option value="0">foo</option>
            <option value="1">bar</option>
            </select>
            HTML,
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#selectizemodel-tags').selectize([]);
            });</script>
            JS,
            $render,
        );
    }

    public function testItemsWithTypeTextInput(): void
    {
        $selectize = Selectize::widget(
            [
                'attribute' => 'tags',
                'items' => ['foo', 'bar'],
                'model' => new SelectizeModel(),
                'type' => Selectize::TYPE_TEXT,
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            '<input type="text" id="selectizemodel-tags" name="SelectizeModel[tags]" value="">',
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#selectizemodel-tags').selectize([]);
            });</script>
            JS,
            $render,
        );
    }

    public function testLoadUrl(): void
    {
        $selectize = Selectize::widget(
            [
                'attribute' => 'tags',
                'loadUrl' => ['/tags'],
                'model' => new SelectizeModel(),
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            <<<HTML
            <select id="selectizemodel-tags" name="SelectizeModel[tags]">

            </select>
            HTML,
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            jQuery('#selectizemodel-tags').selectize({"load":function (query, callback) {
                if (!query.length) return callback();
                $.getJSON('/index.php?r=tags', { query: query }, function (data) {
                    callback(data);
                }).fail(function () {
                    callback();
                });
            }});
            });</script>
            JS,
            $render,
        );
    }

    public function testLoadUrlWithTypeTextInput(): void
    {
        $selectize = Selectize::widget(
            [
                'attribute' => 'tags',
                'loadUrl' => ['/tags'],
                'model' => new SelectizeModel(),
                'type' => Selectize::TYPE_TEXT,
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            '<input type="text" id="selectizemodel-tags" name="SelectizeModel[tags]" value="">',
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#selectizemodel-tags').selectize({"load":function (query, callback) {
                if (!query.length) return callback();
                $.getJSON('/index.php?r=tags', { query: query }, function (data) {
                    callback(data);
                }).fail(function () {
                    callback();
                });
            }});
            });</script>
            JS,
            $render,
        );
    }

    public function testRender(): void
    {
        $selectize = Selectize::widget(
            [
                'attribute' => 'tags',
                'model' => new SelectizeModel(),
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            <<<HTML
            <select id="selectizemodel-tags" name="SelectizeModel[tags]">

            </select>
            HTML,
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#selectizemodel-tags').selectize([]);
            });</script>
            JS,
            $render,
        );
    }

    public function testRenderWithTypeTextInput(): void
    {
        $selectize = Selectize::widget(
            [
                'attribute' => 'tags',
                'model' => new SelectizeModel(),
                'type' => Selectize::TYPE_TEXT,
            ],
        );

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            '<input type="text" id="selectizemodel-tags" name="SelectizeModel[tags]" value="">',
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#selectizemodel-tags').selectize([]);
            });</script>
            JS,
            $render,
        );
    }

    public function testWithoutModel(): void
    {
        $selectize = Selectize::widget(['name' => 'tags']);

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            <<<HTML
            <select id="w0" name="tags">

            </select>
            HTML,
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#w0').selectize([]);
            });</script>
            JS,
            $render,
        );
    }

    public function testWithoutModelWithTypeTextInput(): void
    {
        $selectize = Selectize::widget(['name' => 'tags']);

        $render = $this->view->renderFile(__DIR__ . '/Support/main.php', ['widget' => $selectize]);

        Assert::equalsWithoutLE(
            <<<HTML
            <select id="w0" name="tags">

            </select>
            HTML,
            $selectize,
        );

        $this->assertStringContainsString(
            <<<JS
            <script>jQuery(function ($) {
            jQuery('#w0').selectize([]);
            });</script>
            JS,
            $render,
        );
    }
}
