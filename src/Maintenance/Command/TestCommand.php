<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

use App\Exception\AngularValueException;
use App\Exception\ImpossibleDiscriminantValueException;
use App\Exception\RotatableException;
use App\Exception\SeniorCoefficientException;
use Exception;

class TestCommand extends DefaultCommand
{
    private ?int $exceptionType;

    public function __construct(?int $exceptionType = null)
    {
        $this->exceptionType = $exceptionType;
    }

    /**
     * @throws AngularValueException
     * @throws ImpossibleDiscriminantValueException
     * @throws RotatableException
     * @throws SeniorCoefficientException
     * @throws Exception
     */
    public function execute(): void
    {
        $this->test();
    }

    /**
     * @throws AngularValueException
     * @throws ImpossibleDiscriminantValueException
     * @throws RotatableException
     * @throws SeniorCoefficientException
     * @throws Exception
     */
    private function test(): void
    {
        if ($this->exceptionType === null) {
            echo 'I am valid!';

            return;
        }

        match ($this->exceptionType) {
            1 => throw new RotatableException(),
            2 => throw new AngularValueException(),
            3 => throw new ImpossibleDiscriminantValueException(),
            4 => throw new SeniorCoefficientException(),
            default => throw new Exception(),
        };
    }
}
