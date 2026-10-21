<?php

declare(strict_types=1);

namespace yii2\extensions\selectize;

use yii2\extensions\selectize\asset\SelectizeIntegrationAsset;
use yii\helpers\{Json, Url};
use yii\web\JsExpression;

/**
 * Registers per-widget client settings and the optional remote loader.
 */
trait RegisterClientScript
{
    /**
     * The client options for the Selectize widget.
     *
     * @phpstan-var array<array-key, mixed>
     */
    public array $clientOptions = [];
    /**
     * The URL for loading remote data for the Selectize widget.
     *
     * @phpstan-var array<array-key, string>
     */
    public array $loadUrl = [];
    /**
     * Name of the request parameter that carries the search text when loading remote data.
     */
    public string $queryParam = 'query';

    /**
     * Registers the integration asset and initializes Selectize on the widget input.
     *
     * When {@see $loadUrl} is not empty, adds a `load` callback that requests remote data with the search text.
     */
    public function registerClientScript(): void
    {
        $id = Json::htmlEncode($this->options['id']);

        $clientOptions = $this->clientOptions;

        if ($this->loadUrl !== []) {
            $url = Json::htmlEncode(Url::to($this->loadUrl));
            $queryParam = Json::htmlEncode($this->queryParam);
            $clientOptions['load'] = new JsExpression(
                <<<JS
                function (query, callback) {
                    if (!query.length) return callback();
                    var data = {};
                    data[$queryParam] = query;
                    this.loadRemote($url, data, query, callback);
                }
                JS,
            );
        }

        $options = Json::htmlEncode($clientOptions);

        $view = $this->getView();

        SelectizeIntegrationAsset::register($view);

        $view->registerJs(
            <<<JS
            jQuery(document.getElementById($id)).yiiSelectize($options);
            JS,
        );
    }
}
