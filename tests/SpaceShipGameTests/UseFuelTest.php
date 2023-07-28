<?php

declare(strict_types=1);

namespace App\Tests\SpaceShipGameTests;

use App\Exception\NotEnoughFuelException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\MacroCommand\ShipFlyCommand;
use App\SpaceShipGame\Command\SimpleCommand\BurnFuelCommand;
use App\SpaceShipGame\Command\SimpleCommand\CheckFuelCommand;
use App\SpaceShipGame\Command\SimpleCommand\MoveCommand;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\SpaceObjects\Ship\DefaultShip;
use PHPUnit\Framework\TestCase;

class UseFuelTest extends TestCase
{
    /**
     * @throws NotEnoughFuelException
     * @throws PropertyNotFoundException
     */
    public function testCheckFuel(): void
    {
        $fuel = '1000';
        $fuelStep = '100';
        $speed = '93';
        $maxSpeed = '100';
        $ship = new DefaultShip();

        $ship->setProperty(MovablePropertyEnum::FUEL, $fuel);
        $ship->setProperty(MovablePropertyEnum::FUEL_STEP, $fuelStep);
        $ship->setProperty(MovablePropertyEnum::VELOCITY, $speed);
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxSpeed);

        $checkFuel = new CheckFuelCommand($ship);
        $checkFuel->execute();

        self::assertEquals(1, 1);
    }

    /**
     * @throws NotEnoughFuelException
     * @throws PropertyNotFoundException
     */
    public function testCheckFuelException(): void
    {
        $fuel = '90';
        $fuelStep = '93';
        $speed = '100';
        $maxSpeed = '100';
        $ship = new DefaultShip();

        $ship->setProperty(MovablePropertyEnum::FUEL, $fuel);
        $ship->setProperty(MovablePropertyEnum::FUEL_STEP, $fuelStep);
        $ship->setProperty(MovablePropertyEnum::VELOCITY, $speed);
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxSpeed);

        $checkFuel = new CheckFuelCommand($ship);
        $this->expectException(NotEnoughFuelException::class);
        $checkFuel->execute();
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function testBurnFuel(): void
    {
        $fuel = '1000';
        $fuelStep = '93';
        $speed = '100';
        $maxSpeed = '100';
        $ship = new DefaultShip();

        $ship->setProperty(MovablePropertyEnum::FUEL, $fuel);
        $ship->setProperty(MovablePropertyEnum::FUEL_STEP, $fuelStep);
        $ship->setProperty(MovablePropertyEnum::VELOCITY, $speed);
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxSpeed);

        $burnFuel = new BurnFuelCommand($ship);
        $burnFuel->execute();


        self::assertEquals(bcsub($fuel, $fuelStep), $ship->getProperty(MovablePropertyEnum::FUEL));
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function testMoveBurnFuel(): void
    {
        $fuel = '1000';
        $fuelStep = '93';
        $speed = '100';
        $maxSpeed = '100';
        $position = new Coordinates('12', '5');
        $direction = new Coordinates('-7', '3');

        $ship = new DefaultShip();

        $ship->setProperty(MovablePropertyEnum::POSITION, $position);
        $ship->setProperty(MovablePropertyEnum::FUEL, $fuel);
        $ship->setProperty(MovablePropertyEnum::FUEL_STEP, $fuelStep);
        $ship->setProperty(MovablePropertyEnum::VELOCITY, $speed);
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxSpeed);

        $checkFuel = new CheckFuelCommand($ship);
        $burnFuel = new BurnFuelCommand($ship);
        $moveCommand = new MoveCommand($ship, $direction);

        $moveBurn = new ShipFlyCommand(
            [
                $checkFuel,
                $moveCommand,
                $burnFuel
            ]
        );
        $moveBurn->execute();

        $newCoordinates = $ship->getProperty(MovablePropertyEnum::POSITION);

        self::assertEquals(bcsub($fuel, $fuelStep), $ship->getProperty(MovablePropertyEnum::FUEL));
        self::assertEquals(
            0,
            bccomp($newCoordinates->x, '5'),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->x, '5')
        );
        self::assertEquals(
            0,
            bccomp($newCoordinates->y, '8'),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->y, '8')
        );
    }

}
