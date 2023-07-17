<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

interface MovableInterface
{
    public function setDirection(Coordinates $newVector): void;

    public function moveSpaceObject(): void;
}
