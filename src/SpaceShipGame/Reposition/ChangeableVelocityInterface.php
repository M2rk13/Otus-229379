<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

interface ChangeableVelocityInterface
{
    public function setSpeed(string $speed): void;
}
