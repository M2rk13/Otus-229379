<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

interface RotatableInterface
{
    public function setAngular(int $directionAngular): void;

    public function rotateSpaceObject(): void;
}
