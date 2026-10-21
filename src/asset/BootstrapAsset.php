<?php

declare(strict_types=1);

namespace yii2\extensions\selectize\asset;

use yii\web\AssetBundle;

/**
 * Publishes the prebuilt Bootstrap 5 theme dependencies.
 */
final class BootstrapAsset extends AssetBundle
{
    /**
     * Published distribution directory of the Bootstrap npm package.
     */
    public $sourcePath = '@npm/bootstrap/dist';
    /**
     * Bootstrap stylesheet required by the Selectize Bootstrap 5 theme.
     */
    public $css = ['css/bootstrap.css'];
    /**
     * Bootstrap script bundle, including Popper.
     */
    public $js = ['js/bootstrap.bundle.js'];
}
