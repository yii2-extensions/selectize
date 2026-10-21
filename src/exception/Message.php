<?php

declare(strict_types=1);

namespace yii2\extensions\selectize\exception;

use function sprintf;

/**
 * Defines widget exception message templates.
 *
 * Use {@see Message::getMessage()} to format the template with `sprintf()` arguments.
 */
enum Message: string
{
    /**
     * Error when the widget type is not supported.
     */
    case INVALID_TYPE = 'Invalid type "%s".';

    /**
     * Returns the formatted message string for the error case.
     *
     * @param int|string ...$argument Values to insert into the message template.
     *
     * @return string Formatted error message with interpolated arguments.
     */
    public function getMessage(int|string ...$argument): string
    {
        return sprintf($this->value, ...$argument);
    }
}
