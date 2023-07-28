<?php

declare(strict_types=1);

namespace App\Tests\SpaceShipGameTests;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\SimpleCommand\RotateCommand;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\RotatableAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultStaticObject;
use App\SpaceShipGame\SpaceObjects\Ship\DefaultShip;
use PHPUnit\Framework\TestCase;
use TypeError;

class RotateTest extends TestCase
{
    /**
     * @dataProvider rotateDataProvider
     *
     * @throws PropertyNotFoundException
     * @throws AngularValueException
     */
    public function testRotate(int $startAngular, int $rotateAngular, int $expectedValue): void
    {
        $ship = new DefaultShip();
        $ship->setProperty(MovablePropertyEnum::ANGULAR_POSITION, $startAngular);

        $rotatableShip = new RotatableAdapter($ship);

        $rotatableShip->setAngular($rotateAngular);
        $rotatableShip->rotateSpaceObject();

        self::assertSame($expectedValue, $ship->getProperty(MovablePropertyEnum::ANGULAR_POSITION));
    }

    public static function rotateDataProvider(): array
    {
        return [
            'validRotate' => [
                'startAngular' => 0,
                'rotateAngular' => 45,
                'expectedValue' => 45,
            ],
            'validBackRotate' => [
                'startAngular' => 90,
                'rotateAngular' => -100,
                'expectedValue' => -10,
            ],
        ];
    }

    /**
     * @dataProvider rotateErrorsProvider
     *
     * @throws AngularValueException
     * @throws PropertyNotFoundException
     */
    public function testRotateErrors(
        string $object,
        ?int $startAngular,
        ?int $rotateAngular,
        string $expectedException
    ): void
    {
        $this->expectException($expectedException);
        $ship = new $object();

        if ($startAngular !== null) {
            $ship->setProperty(MovablePropertyEnum::ANGULAR_POSITION, $startAngular);
        }

        $rotatableShip = new RotatableAdapter($ship);

        if ($rotateAngular !== null) {
            $rotatableShip->setAngular($rotateAngular);
        }

        $rotatableShip->rotateSpaceObject();
    }

    public static function rotateErrorsProvider(): array
    {
        return [
            'noStartValue' => [
                'object' => DefaultShip::class,
                'startAngular' => null,
                'rotateAngular' => 45,
                'expectedException' => PropertyNotFoundException::class,
            ],
            'noMoveValue' => [
                'object' => DefaultShip::class,
                'startAngular' => 0,
                'rotateAngular' => null,
                'expectedException' => PropertyNotFoundException::class,
            ],
            'valueOverMaxCount' => [
                'object' => DefaultShip::class,
                'startAngular' => 0,
                'rotateAngular' => 190,
                'expectedException' => AngularValueException::class,
            ],

            'notMovableObject' => [
                'object' => DefaultStaticObject::class,
                'startAngular' => 0,
                'rotateAngular' => 190,
                'expectedException' => TypeError::class,
            ],
        ];
    }

    /**
     * @dataProvider rotateDataProvider
     *
     * @throws PropertyNotFoundException
     * @throws AngularValueException
     */
    public function testRotateCommand(int $startAngular, int $rotateAngular, int $expectedValue): void
    {
        $ship = new DefaultShip();
        $ship->setProperty(MovablePropertyEnum::ANGULAR_POSITION, $startAngular);

        $rotateCommand = new RotateCommand($ship, $rotateAngular);
        $rotateCommand->execute();

        self::assertSame($expectedValue, $ship->getProperty(MovablePropertyEnum::ANGULAR_POSITION));
    }
}
