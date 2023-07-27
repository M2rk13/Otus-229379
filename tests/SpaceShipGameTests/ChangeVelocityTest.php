<?php

declare(strict_types=1);

namespace App\Tests\SpaceShipGameTests;

use App\Exception\MaxSpeedException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\SimpleCommand\ChangeVelocityCommand;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\ChangeableVelocityAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;
use App\SpaceShipGame\SpaceObjects\Ship\DefaultShip;
use PHPUnit\Framework\TestCase;

class ChangeVelocityTest extends TestCase
{
    /**
     * @dataProvider rotateDataProvider
     *
     * @throws PropertyNotFoundException
     * @throws MaxSpeedException
     */
    public function testRotate(string $speed, string $maxSpeed, string $expectedValue): void
    {
        $ship = new DefaultShip();
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxSpeed);

        $rotatableShip = new ChangeableVelocityAdapter($ship);
        $rotatableShip->setSpeed($speed);

        self::assertSame($expectedValue, $ship->getProperty(MovablePropertyEnum::VELOCITY));
    }

    public static function rotateDataProvider(): array
    {
        return [
            'validSpeed' => [
                'speed' => '5',
                'maxSpeed' => '10',
                'expectedValue' => '5',
            ],
        ];
    }

    /**
     * @dataProvider rotateErrorsProvider
     *
     * @throws MaxSpeedException
     * @throws PropertyNotFoundException
     */
    public function testRotateErrors(
        string $speed,
        string $maxSpeed,
        string $expectedException
    ): void
    {
        $this->expectException($expectedException);
        $ship = new DefaultMovableObject();
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxSpeed);
        $command = new ChangeVelocityCommand($ship, $speed);
        $command->execute();
    }

    public static function rotateErrorsProvider(): array
    {
        return [
            'notValidSpeed' => [
                'speed' => '50',
                'maxSpeed' => '10',
                'expectedValue' => MaxSpeedException::class,
            ],
        ];
    }

    /**
     * @dataProvider rotateDataProvider
     *
     * @throws MaxSpeedException
     * @throws PropertyNotFoundException
     */
    public function testSpeedCommand(string $speed, string $maxSpeed, string $expectedValue): void
    {
        $ship = new DefaultShip();
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxSpeed);
        $command = new ChangeVelocityCommand($ship, $speed);
        $command->execute();

        self::assertSame($expectedValue, $ship->getProperty(MovablePropertyEnum::VELOCITY));
    }
}
