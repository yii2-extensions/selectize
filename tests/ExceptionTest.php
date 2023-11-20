<?php

declare(strict_types=1);

namespace Yii2\Extensions\Selectize\Tests;

use Yii2\Extensions\Selectize\Selectize;
use Yii2\Extensions\Selectize\Tests\Support\SelectizeModel;
use yii\base\InvalidArgumentException;

final class ExceptionTest extends TestCase
{
    public function testTypeNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type "not-exist".');

        Selectize::widget(
            [
                'attribute' => 'content',
                'model' => new SelectizeModel(),
                'type' => 'not-exist',
            ],
        );
    }
}
