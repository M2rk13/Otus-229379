<?php

declare(strict_types=1);

namespace App\Exception;

use function sprintf;

class AngularValueException extends RotatableException
{
    private const MESSAGE = 'angular value is not valid';

    public function __construct(string $message = '')
    {
        parent::__construct(sprintf('%s: %s', self::MESSAGE, $message));
    }
}
