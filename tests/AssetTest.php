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
use yii\web\View;

final class AssetTest extends TestCase
{
    public function testSelectizeAssetSimpleDependency(): void
    {
        $this->mockApplication();

        $view = Yii::$app->getView();

        $this->assertEmpty($view->assetBundles);

        SelectizeAsset::register($view);

        $this->assertCount(4, $view->assetBundles);

        $this->assertArrayHasKey(SelectizeAsset::class, $view->assetBundles);
        $this->assertArrayHasKey(BootstrapAsset::class, $view->assetBundles);
        $this->assertArrayHasKey(BootstrapPluginAsset::class, $view->assetBundles);
        $this->assertArrayHasKey(JqueryAsset::class, $view->assetBundles);
    }

    public function testFileSelectizeAssetRegister(): void
    {
        $this->mockApplication();

        $view = new View();

        $this->assertEmpty($view->assetBundles);

        SelectizeAsset::register($view);

        $this->assertCount(4, $view->assetBundles);
        $this->assertInstanceOf(AssetBundle::class, $view->assetBundles[SelectizeAsset::class]);

        $result = $view->renderFile(
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
