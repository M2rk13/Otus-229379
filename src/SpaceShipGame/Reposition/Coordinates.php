<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

class Coordinates
{
    public readonly string $x;
    public readonly string $y;

    public function __construct(string $x, string $y) {
        $this->x = $x;
        $this->y = $y;
    }
}
