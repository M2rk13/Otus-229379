<?php

declare(strict_types=1);

namespace App\Exception;

use function sprintf;

class MaxSpeedException extends SpeedException
{
    private const MESSAGE = 'value exceeds the maximum speed';

    public function __construct(string $message = '')
    {
        parent::__construct(sprintf('%s: %s', self::MESSAGE, $message));
    }
}
