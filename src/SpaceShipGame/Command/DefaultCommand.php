<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command;

class DefaultCommand implements CommandInterface
{
    protected const DEFAULT_FLOAT_SCALE = 13;

    public function execute(): int
    {
        return CommandStatusEnum::SUCCESS;
    }
}
