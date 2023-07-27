<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command\MacroCommand;

use App\SpaceShipGame\Command\CommandStatusEnum;
use App\SpaceShipGame\Command\DefaultCommand;

class ShipFlyCommand extends DefaultCommand
{
    /**
     * @var DefaultCommand[]
     */
    private array $commandList;

    /**
     * @param DefaultCommand[] $commandList
     */
    public function __construct(array $commandList)
    {
        $this->commandList = $commandList;
    }

    public function execute(): int
    {
        foreach ($this->commandList as $command) {
            $command->execute();
        }

        return CommandStatusEnum::SUCCESS;
    }
}
