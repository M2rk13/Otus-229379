<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

use Exception;
use TypeError;

class MultipleRepeatCommand
{
    private DefaultCommand $command;
    private int $counter;
    private int $repeatCount;

    public function __construct(DefaultCommand $command, int $repeatCount = 2)
    {
        if ($repeatCount <= 0) {
            throw new TypeError('only positive value is allowed');
        }

        $this->repeatCount = $repeatCount;
        $this->command = $command;
        $this->counter = 0;
    }

    public function execute(): void
    {
        while (true) {
            ++$this->counter;

            try {
                $this->command->execute();

                break;
            } catch (Exception $e) {
                if ($this->counter === $this->repeatCount) {
                    $logCommand = new LogThrowableCommand($e);
                    $logCommand->execute();

                    break;
                }
            }
        }
    }
}
