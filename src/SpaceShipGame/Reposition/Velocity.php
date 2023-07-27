<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

class Velocity
{
    public readonly string $speed;

    public function __construct(string $speed) {
        $this->speed = $speed;
    }
}
