<?php

declare(strict_types=1);

namespace yii2\extensions\selectize\asset;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Publishes Selectize with selector-safe labels and collision-safe option maps.
 */
final class SelectizeRuntimeAsset extends AssetBundle
{
    /**
     * Published source directory of the packaged scripts.
     */
    public $sourcePath = __DIR__ . '/js';
    /**
     * Packaged Selectize 0.15.2 runtime with the local compatibility changes.
     */
    public $js = ['selectize/selectize.js'];
    /**
     * jQuery, required by the Selectize runtime.
     */
    public $depends = [JqueryAsset::class];
}
