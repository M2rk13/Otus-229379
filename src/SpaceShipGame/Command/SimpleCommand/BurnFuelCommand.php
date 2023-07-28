<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command\SimpleCommand;

use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\CommandStatusEnum;
use App\SpaceShipGame\Command\DefaultCommand;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class BurnFuelCommand extends DefaultCommand
{
    private DefaultMovableObject $object;

    public function __construct(DefaultMovableObject $object)
    {
        $this->object = $object;
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function execute(): int
    {
        $fuel = $this->object->getProperty(MovablePropertyEnum::FUEL);
        $fuelStep = $this->object->getProperty(MovablePropertyEnum::FUEL_STEP);
        $speed = $this->object->getProperty(MovablePropertyEnum::VELOCITY);
        $maxSpeed = $this->object->getProperty(MovablePropertyEnum::MAX_VELOCITY);

        $burnFuel = bcmul($fuelStep, bcdiv($speed, $maxSpeed, self::DEFAULT_FLOAT_SCALE), 0);
        $restFuel = bcsub($fuel, $burnFuel);
        $this->object->setProperty(MovablePropertyEnum::FUEL, $restFuel);

        return CommandStatusEnum::SUCCESS;
    }
}
