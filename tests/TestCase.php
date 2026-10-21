<?php

declare(strict_types=1);

namespace yii2\extensions\selectize\tests;

use PHPForge\Support\Assert;
use Yii;
use yii\di\Container;
use yii\web\Application;
use yii\web\View;

/**
 * This is the base class for all yii framework unit tests.
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected View $view;

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    protected function mockApplication(): void
    {
        new Application(
            [
                'id' => 'testapp',
                'aliases' => [
                    '@root' => dirname(__DIR__),
                    '@bower' => '@vendor/bower-asset',
                    '@npm' => '@vendor/npm-asset',
                ],
                'basePath' => dirname(__DIR__),
                'components' => [
                    'assetManager' => [
                        'basePath' => __DIR__ . '/support/runtime',
                        'baseUrl' => '/',
                    ],
                    'request' => [
                        'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
                        'scriptFile' => __DIR__ . '/index.php',
                        'scriptUrl' => '/index.php',
                    ],
                ],
            ],
        );
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        Yii::$app = null;
        Yii::$container = new Container();

        Assert::removeFilesFromDirectory(__DIR__ . '/support/runtime');

        unset($this->view);
    }
}
