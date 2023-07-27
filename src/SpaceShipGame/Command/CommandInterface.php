<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command;

interface CommandInterface
{
    public function execute(): int;
}
