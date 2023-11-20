<?php

declare(strict_types=1);

namespace Yii2\Extensions\Selectize;

use yii\base\InvalidArgumentException;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class Selectize extends InputWidget
{
    use RegisterClientScript;

    public const TYPE_SELECT = 'select';
    public const TYPE_TEXT = 'text';
    public array $items = [];
    public string $type = self::TYPE_SELECT;

    /**
     * @inheritdoc
     */
    public function run(): string
    {

        $html = match ($this->type) {
            static::TYPE_SELECT => $this->generateDropdownList(),
            static::TYPE_TEXT => $this->generateTextInput(),
            default => throw new InvalidArgumentException('Invalid type "' . $this->type . '".'),
        };

        $this->registerClientScript();

        return $html;
    }

    private function generateTextInput(): string
    {
        return match ($this->hasModel()) {
            true => Html::activeTextInput($this->model, $this->attribute, $this->options),
            default => Html::textInput($this->name, $this->value, $this->options),
        };
    }

    private function generateDropdownList(): string
    {
        return match ($this->hasModel()) {
            true => Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options),
            default => Html::dropDownList($this->name, $this->value, $this->items, $this->options),
        };
    }
}
