<?php

declare(strict_types=1);

namespace yii2\extensions\selectize\asset;

use yii\web\{AssetBundle, JqueryAsset};

/**
 * Publishes the Selectize Bootstrap 5 theme and compatible runtime.
 */
final class SelectizeAsset extends AssetBundle
{
    /**
     * Published distribution directory of the Selectize npm package.
     */
    public $sourcePath = '@npm/@selectize/selectize/dist';
    /**
     * Selectize Bootstrap 5 theme stylesheet.
     */
    public $css = ['css/selectize.bootstrap5.css'];
    /**
     * Bootstrap, jQuery and the packaged Selectize runtime that the theme styles.
     */
    public $depends = [
        BootstrapAsset::class,
        JqueryAsset::class,
        SelectizeRuntimeAsset::class,
    ];
}
