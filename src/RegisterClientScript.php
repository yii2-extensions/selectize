<?php

declare(strict_types=1);

namespace Yii2\Extensions\Selectize;

use Yii2\Extensions\Selectize\Asset\SelectizeAsset;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

trait RegisterClientScript
{
    /**
     * @phpstan-var array<array-key, mixed>
     */
    public array $clientOptions = [];
    /**
     * @phpstan-var array<array-key, string>
     */
    public array $loadUrl = [];
    public string $queryParam = 'query';

    public function registerClientScript(): void
    {
        $id = $this->options['id'];

        if ($this->loadUrl !== []) {
            $url = Url::to($this->loadUrl);
            $this->clientOptions['load'] = new JsExpression(
                $loadFunction = <<<JS
                function (query, callback) {
                    if (!query.length) return callback();
                    $.getJSON('$url', { {$this->queryParam}: query }, function (data) {
                        callback(data);
                    }).fail(function () {
                        callback();
                    });
                }
                JS
            );
        }

        $options = Json::encode($this->clientOptions);
        $view = $this->getView();

        SelectizeAsset::register($view);

        $view->registerJs(
            <<<JS
            jQuery('#$id').selectize($options);
            JS
        );
    }
}
