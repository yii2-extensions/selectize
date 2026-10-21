<?php

declare(strict_types=1);

namespace yii2\extensions\selectize\asset;

use yii\web\AssetBundle;

/**
 * Adds accessible input attributes and repeatable widget initialization.
 */
final class SelectizeIntegrationAsset extends AssetBundle
{
    /**
     * Published source directory of the packaged scripts.
     */
    public $sourcePath = __DIR__ . '/js';
    /**
     * Integration script that provides the `yiiSelectize` jQuery plugin.
     */
    public $js = ['selectize.yii.js'];
    /**
     * Selectize theme and runtime required by the integration script.
     */
    public $depends = [SelectizeAsset::class];
}
