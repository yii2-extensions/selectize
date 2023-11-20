<?php

declare(strict_types=1);

namespace Yii2\Extensions\Selectize\Tests;

use Yii2\Asset\BootstrapPluginAsset;
use Yii2\Extensions\Selectize\Asset\SelectizeAsset;
use Yii;
use Yii2\Asset\BootstrapAsset;
use Yii2\Extensions\Selectize\Selectize;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

final class AssetTest extends TestCase
{
    public function setup(): void
    {
        parent::setUp();
        $this->mockApplication();

        Selectize::$counter = 0;

        $this->view = Yii::$app->getView();
    }

    public function testSelectizeAssetSimpleDependency(): void
    {
        $this->assertEmpty($this->view->assetBundles);

        SelectizeAsset::register($this->view);

        $this->assertCount(4, $this->view->assetBundles);

        $this->assertArrayHasKey(SelectizeAsset::class, $this->view->assetBundles);
        $this->assertArrayHasKey(BootstrapAsset::class, $this->view->assetBundles);
        $this->assertArrayHasKey(BootstrapPluginAsset::class, $this->view->assetBundles);
        $this->assertArrayHasKey(JqueryAsset::class, $this->view->assetBundles);
    }

    public function testFileSelectizeAssetRegister(): void
    {
        $this->assertEmpty($this->view->assetBundles);

        SelectizeAsset::register($this->view);

        $this->assertCount(4, $this->view->assetBundles);
        $this->assertInstanceOf(AssetBundle::class, $this->view->assetBundles[SelectizeAsset::class]);

        $result = $this->view->renderFile(
            __DIR__ . '/Support/main.php',
            ['widget' => Selectize::widget(['name' => 'tags', 'id' => 'tests-id'])],
        );

        $this->assertStringContainsString('bootstrap.css', $result);
        $this->assertStringContainsString('selectize.bootstrap5.css', $result);
        $this->assertStringContainsString('bootstrap.bundle.js', $result);
        $this->assertStringContainsString('jquery.js', $result);
        $this->assertStringContainsString('selectize.js', $result);
    }
}
