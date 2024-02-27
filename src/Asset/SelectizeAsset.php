<?php

declare(strict_types=1);

namespace Yii2\Extensions\Selectize\Asset;

use Yii2\Asset\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

final class SelectizeAsset extends AssetBundle
{
    public $sourcePath = '@npm/selectize--selectize/dist';

    /**
     * @phpstan-var array<array-key, mixed>
     */
    public $css = [
        'css/selectize.bootstrap5.css',
    ];

    /**
     * @phpstan-var array<array-key, mixed>
     */
    public $js = [
        'js/selectize.js',
    ];

    /**
     * @phpstan-var array<array-key, mixed>
     */
    public $depends = [
        BootstrapPluginAsset::class,
        JqueryAsset::class,
    ];
}
