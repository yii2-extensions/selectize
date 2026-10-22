<?php

declare(strict_types=1);

namespace yii2\extensions\selectize\tests;

use PHPUnit\Framework\Attributes\TestWith;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\{AssetBundle, JqueryAsset, JsExpression, View};
use yii2\extensions\selectize\asset\{BootstrapAsset, SelectizeAsset, SelectizeIntegrationAsset, SelectizeRuntimeAsset};
use yii2\extensions\selectize\exception\Message;
use yii2\extensions\selectize\Selectize;
use yii2\extensions\selectize\tests\support\SelectizeModel;

/**
 * Unit tests for the {@see Selectize} widget markup, client integration settings, asset bundles and type validation.
 */
final class SelectizeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->mockApplication();

        Selectize::$counter = 0;

        $this->view = Yii::$app->getView();
    }

    public function testCustomLoaderAndRendererRemainExecutable(): void
    {
        Selectize::widget(
            [
                'name' => 'tag',
                'clientOptions' => [
                    'load' => new JsExpression('function (query, callback) { callback([]); }'),
                    'render' => [
                        'option' => new JsExpression(
                            'function (item, escape) { return "<b>" + escape(item.text) + "</b>"; }',
                        ),
                    ],
                ],
            ],
        );

        self::assertSame(
            [
                <<<'JS'
                jQuery(document.getElementById("w0")).yiiSelectize({"load":function (query, callback) { callback([]); },"render":{"option":function (item, escape) { return "<b>" + escape(item.text) + "</b>"; }}});
                JS,
            ],
            array_values($this->view->js[View::POS_READY]),
            'Loader and renderer must stay executable functions.',
        );
    }

    public function testDefaultEncodingAndIntentionalOptionMarkup(): void
    {
        $encoded = Selectize::widget(
            [
                'name' => 'safe',
                'items' => ['a' => '<b>Label</b>'],
            ],
        );
        $markup = Selectize::widget(
            [
                'name' => 'markup',
                'items' => ['a' => '<b>Label</b>'],
                'options' => ['encode' => false],
            ],
        );

        self::assertSame(
            <<<HTML
            <select id="w0" name="safe">
            <option value="a">&lt;b&gt;Label&lt;/b&gt;</option>
            </select>
            HTML,
            $encoded,
            'Labels must be encoded by default.',
        );
        self::assertSame(
            <<<HTML
            <select id="w1" name="markup">
            <option value="a"><b>Label</b></option>
            </select>
            HTML,
            $markup,
            'Labels must keep markup when `encode` is `false`.',
        );
    }

    public function testFileSelectizeAssetRegister(): void
    {
        self::assertEmpty(
            $this->view->assetBundles,
            'No asset bundles should be registered before rendering.',
        );

        SelectizeAsset::register($this->view);

        self::assertCount(
            4,
            $this->view->assetBundles,
            'Rendering should register all four asset bundles.',
        );
        self::assertInstanceOf(
            AssetBundle::class,
            $this->view->assetBundles[SelectizeAsset::class],
            'The registered Selectize asset should be an asset bundle.',
        );

        $result = $this->view->renderFile(
            __DIR__ . '/support/main.php',
            ['widget' => Selectize::widget(['name' => 'tags', 'id' => 'tests-id'])],
        );

        self::assertStringContainsString(
            'bootstrap.css',
            $result,
            'The Bootstrap stylesheet should be rendered.',
        );
        self::assertStringContainsString(
            'selectize.bootstrap5.css',
            $result,
            'The Selectize stylesheet should be rendered.',
        );
        self::assertStringContainsString(
            'bootstrap.bundle.js',
            $result,
            'The Bootstrap script should be rendered.',
        );
        self::assertStringContainsString(
            'jquery.js',
            $result,
            'The jQuery script should be rendered.',
        );
        self::assertStringContainsString(
            'selectize.js',
            $result,
            'The Selectize script should be rendered.',
        );
    }

    public function testId(): void
    {
        $selectize = Selectize::widget(
            [
                'name' => 'tags',
                'id' => 'tests-id',
            ],
        );

        self::assertSame(
            <<<HTML
            <select id="tests-id" name="tags">

            </select>
            HTML,
            $selectize,
            'Select must use the explicit ID.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("tests-id")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the explicit ID.',
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

        self::assertSame(
            '<input type="text" id="tests-id" name="tags">',
            $selectize,
            'Text input must use the explicit ID.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("tests-id")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the explicit ID.',
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

        self::assertSame(
            <<<HTML
            <select id="selectizemodel-tags" name="SelectizeModel[tags]">
            <option value="0">foo</option>
            <option value="1">bar</option>
            </select>
            HTML,
            $selectize,
            'Items must render as options.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("selectizemodel-tags")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the attribute ID.',
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

        self::assertSame(
            '<input type="text" id="selectizemodel-tags" name="SelectizeModel[tags]" value="">',
            $selectize,
            'Text input must not render items.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("selectizemodel-tags")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the attribute ID.',
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

        self::assertSame(
            <<<HTML
            <select id="selectizemodel-tags" name="SelectizeModel[tags]">

            </select>
            HTML,
            $selectize,
            'Remote select must render without options.',
        );
        self::assertSame(
            [
                <<<'JS'
                jQuery(document.getElementById("selectizemodel-tags")).yiiSelectize({"load":function (query, callback) {
                    if (!query.length) return callback();
                    var data = {};
                    data["query"] = query;
                    this.loadRemote("\/index.php?r=tags", data, query, callback);
                }});
                JS,
            ],
            array_values($this->view->js[View::POS_READY]),
            'Loader must request the resolved URL.',
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

        self::assertSame(
            '<input type="text" id="selectizemodel-tags" name="SelectizeModel[tags]" value="">',
            $selectize,
            'Remote text input must render empty.',
        );
        self::assertSame(
            [
                <<<'JS'
                jQuery(document.getElementById("selectizemodel-tags")).yiiSelectize({"load":function (query, callback) {
                    if (!query.length) return callback();
                    var data = {};
                    data["query"] = query;
                    this.loadRemote("\/index.php?r=tags", data, query, callback);
                }});
                JS,
            ],
            array_values($this->view->js[View::POS_READY]),
            'Loader must request the resolved URL.',
        );
    }

    public function testPublicClientRegistrationIncludesIntegrationAssets(): void
    {
        $widget = new Selectize(
            [
                'id' => 'registered-topic',
                'name' => 'topic',
            ],
        );

        $widget->registerClientScript();

        self::assertArrayHasKey(
            SelectizeIntegrationAsset::class,
            $this->view->assetBundles,
            'Integration bundle must be registered.',
        );
        self::assertStringContainsString(
            'selectize.yii.js',
            $this->view->renderFile(__DIR__ . '/support/main.php', ['widget' => '']),
            'Integration script must be rendered.',
        );
    }

    public function testRemoteSettingsAreEncodedWithoutMutatingClientOptions(): void
    {
        $customLoad = new JsExpression('function (query, callback) { callback([]); }');

        $options = [
            'load' => $customLoad,
            'placeholder' => '</script><b>Choose</b>',
        ];

        $widget = new Selectize(
            [
                'id' => 'quoted"id',
                'name' => 'tag',
                'loadUrl' => ['/search', 'fixed' => "a'b"],
                'queryParam' => 'search-term"</script>',
                'clientOptions' => $options,
            ],
        );

        $widget->run();

        self::assertSame(
            $options,
            $widget->clientOptions,
            'Client options must not be mutated.',
        );
        self::assertSame(
            [
                <<<'JS'
                jQuery(document.getElementById("quoted\u0022id")).yiiSelectize({"load":function (query, callback) {
                    if (!query.length) return callback();
                    var data = {};
                    data["search-term\u0022\u003C\/script\u003E"] = query;
                    this.loadRemote("\/index.php?r=search\u0026fixed=a%27b", data, query, callback);
                },"placeholder":"\u003C\/script\u003E\u003Cb\u003EChoose\u003C\/b\u003E"});
                JS,
            ],
            array_values($this->view->js[View::POS_READY]),
            'Built-in loader must replace the custom one with encoded values.',
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

        self::assertSame(
            <<<HTML
            <select id="selectizemodel-tags" name="SelectizeModel[tags]">

            </select>
            HTML,
            $selectize,
            'Select must use the attribute ID and name.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("selectizemodel-tags")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the attribute ID.',
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

        self::assertSame(
            '<input type="text" id="selectizemodel-tags" name="SelectizeModel[tags]" value="">',
            $selectize,
            'Text input must use the attribute ID and name.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("selectizemodel-tags")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the attribute ID.',
        );
    }

    public function testSelectedValuesAndMultipleSubmission(): void
    {
        $html = Selectize::widget(
            [
                'id' => 'tags:one',
                'name' => 'tags',
                'value' => ['yii'],
                'items' => ['php' => 'PHP', 'yii' => 'Yii'],
                'options' => ['multiple' => true, 'disabled' => true],
            ],
        );

        self::assertSame(
            <<<HTML
            <select id="tags:one" name="tags[]" disabled multiple size="4">
            <option value="php">PHP</option>
            <option value="yii" selected>Yii</option>
            </select>
            HTML,
            $html,
            'Select must submit an array, keep its state and select the current value.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("tags:one")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target IDs with selector characters.',
        );
    }

    public function testSelectizeAssetSimpleDependency(): void
    {
        self::assertEmpty(
            $this->view->assetBundles,
            'No asset bundles should be registered yet.',
        );

        SelectizeAsset::register($this->view);

        self::assertCount(
            4,
            $this->view->assetBundles,
            'All four asset bundles should be registered.',
        );
        self::assertArrayHasKey(
            SelectizeAsset::class,
            $this->view->assetBundles,
            'The Selectize asset bundle should be registered.',
        );
        self::assertArrayHasKey(
            BootstrapAsset::class,
            $this->view->assetBundles,
            'The Bootstrap asset bundle should be registered.',
        );
        self::assertArrayHasKey(
            JqueryAsset::class,
            $this->view->assetBundles,
            'The jQuery asset bundle should be registered.',
        );
        self::assertArrayHasKey(
            SelectizeRuntimeAsset::class,
            $this->view->assetBundles,
            'The Selectize runtime asset bundle should be registered.',
        );
    }

    #[TestWith([false])]
    #[TestWith([true])]
    public function testStandaloneMultipleClearingMarker(bool $disabled): void
    {
        $html = Selectize::widget(
            [
                'name' => 'tags',
                'value' => ['yii'],
                'items' => ['yii' => 'Yii'],
                'options' => ['multiple' => true, 'unselect' => '', 'disabled' => $disabled],
            ],
        );

        $state = $disabled ? ' disabled' : '';

        self::assertSame(
            <<<HTML
            <input type="hidden" name="tags" value=""{$state}><select id="w0" name="tags[]"{$state} multiple size="4">
            <option value="yii" selected>Yii</option>
            </select>
            HTML,
            $html,
            'Marker and array select must share the disabled state.',
        );
    }

    public function testTextInputSubmitsAStringAndIgnoresPhpItems(): void
    {
        $html = Selectize::widget(
            [
                'name' => 'keywords',
                'value' => 'php,yii',
                'type' => Selectize::TYPE_TEXT,
                'items' => ['ignored' => 'Not rendered'],
            ],
        );

        self::assertSame(
            '<input type="text" id="w0" name="keywords" value="php,yii">',
            $html,
            'Value must stay a comma-separated string without items.',
        );
    }

    public function testThrowInvalidArgumentExceptionForInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::INVALID_TYPE->getMessage('not-exist'),
        );

        Selectize::widget(
            [
                'attribute' => 'content',
                'model' => new SelectizeModel(),
                'type' => 'not-exist',
            ],
        );
    }

    public function testWithoutModel(): void
    {
        $selectize = Selectize::widget(['name' => 'tags']);

        self::assertSame(
            <<<HTML
            <select id="w0" name="tags">

            </select>
            HTML,
            $selectize,
            'Select must use the generated ID.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("w0")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the generated ID.',
        );
    }

    public function testWithoutModelWithTypeTextInput(): void
    {
        $selectize = Selectize::widget(
            [
                'name' => 'tags',
                'type' => Selectize::TYPE_TEXT,
            ],
        );

        self::assertSame(
            '<input type="text" id="w0" name="tags">',
            $selectize,
            'Text input must use the generated ID.',
        );
        self::assertSame(
            ['jQuery(document.getElementById("w0")).yiiSelectize([]);'],
            array_values($this->view->js[View::POS_READY]),
            'Initialization must target the generated ID.',
        );
    }
}
