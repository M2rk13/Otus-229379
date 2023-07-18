<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

class RepeaterCommand
{
    private DefaultCommand $command;

    public function __construct(DefaultCommand $command)
    {
        $this->command = $command;
    }

    public function execute(): void
    {
        $this->command->execute();
    }
}
