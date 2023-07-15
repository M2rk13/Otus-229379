<?php

namespace App\SpaceShipGame\Reposition;

interface RotatableInterface
{
    public function setAngular(int $directionAngular): void;

    public function rotateSpaceObject(): void;
}
