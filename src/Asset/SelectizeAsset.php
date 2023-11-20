<?php

declare(strict_types=1);

namespace Yii2\Extensions\Selectize\Asset;

use Yii2\Asset\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

final class SelectizeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@npm/selectize--selectize/dist';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/selectize.bootstrap5.css',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/selectize.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        BootstrapPluginAsset::class,
        JqueryAsset::class,
    ];
}
