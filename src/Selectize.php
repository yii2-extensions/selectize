<?php

declare(strict_types=1);

namespace yii2\extensions\selectize;

use yii\base\InvalidArgumentException;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use yii2\extensions\selectize\exception\Message;

/**
 * Renders model-bound or standalone Selectize selects and text inputs.
 */
class Selectize extends InputWidget
{
    use RegisterClientScript;

    /**
     * The type of the Selectize widget.
     */
    public const string TYPE_SELECT = 'select';
    /**
     * The type of the Selectize widget for text inputs.
     */
    public const string TYPE_TEXT = 'text';
    /**
     * The items for the Selectize dropdown list.
     *
     * @phpstan-var array<array-key, mixed>
     */
    public array $items = [];
    /**
     * The type of the Selectize widget.
     */
    public string $type = self::TYPE_SELECT;

    /**
     * Renders the input for the configured type and registers the client script.
     *
     * @throws InvalidArgumentException if {@see $type} is neither {@see TYPE_SELECT} nor {@see TYPE_TEXT}.
     *
     * @return string Rendered input HTML.
     */
    public function run(): string
    {
        $html = match ($this->type) {
            static::TYPE_SELECT => $this->generateDropdownList(),
            static::TYPE_TEXT => $this->generateTextInput(),
            default => throw new InvalidArgumentException(Message::INVALID_TYPE->getMessage($this->type)),
        };

        $this->registerClientScript();

        return $html;
    }

    /**
     * Renders a model-bound or standalone text input.
     *
     * @return string Rendered text input HTML.
     */
    private function generateTextInput(): string
    {
        return match ($this->hasModel()) {
            true => Html::activeTextInput($this->model, $this->attribute, $this->options),
            default => Html::textInput($this->name, $this->value, $this->options),
        };
    }

    /**
     * Renders a model-bound or standalone dropdown list with the configured items.
     *
     * @return string Rendered dropdown list HTML.
     */
    private function generateDropdownList(): string
    {
        return match ($this->hasModel()) {
            true => Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options),
            default => Html::dropDownList($this->name, $this->value, $this->items, $this->options),
        };
    }
}
