<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Enum;

enum MovablePropertyEnum
{
    public const DIRECTION = 'direction';
    public const POSITION = 'position';
    public const ANGULAR_DIRECTION = 'directionAngular';
    public const ANGULAR_POSITION = 'directionPosition';
    public const VELOCITY = 'velocity';
    public const MAX_VELOCITY = 'maxVelocity';
    public const WEIGHT = 'weight';
    public const FUEL = 'fuel';
    public const FUEL_STEP = 'fuel_step';
}
