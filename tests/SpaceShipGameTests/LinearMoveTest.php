<?php

declare(strict_types=1);

namespace App\Tests\SpaceShipGameTests;

use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\Reposition\MovableAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultStaticObject;
use App\SpaceShipGame\SpaceObjects\Ship\DefaultShip;
use PHPUnit\Framework\TestCase;
use TypeError;

class LinearMoveTest extends TestCase
{
    /**
     * @dataProvider linearMoveDataProvider
     *
     * @throws PropertyNotFoundException
     */
    public function testLinearMove(array $coordinates, array $vector, array $expectedValue): void
    {
        $ship = new DefaultShip();
        $position = new Coordinates(...$coordinates);
        $ship->setProperty(MovablePropertyEnum::POSITION, $position);

        $movableShip = new MovableAdapter($ship);

        $direction = new Coordinates(...$vector);
        $movableShip->setDirection($direction);

        $movableShip->moveSpaceObject();
        $newCoordinates = $ship->getProperty(MovablePropertyEnum::POSITION);

        self::assertEquals(
            0,
            bccomp($newCoordinates->x, $expectedValue['x']),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->x, $expectedValue['x'])
        );
        self::assertEquals(
            0,
            bccomp($newCoordinates->y, $expectedValue['y']),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->x, $expectedValue['x'])
        );
    }

    public static function linearMoveDataProvider(): array
    {
        return [
            'correctLinearMove' => [
                'coordinates' => [
                    'x' => '12',
                    'y' => '5',
                ],
                'vector' => [
                    'x' => '-7',
                    'y' => '3'
                ],
                'expectedValue' => [
                    'x' => '5',
                    'y' => '8',
                ],
            ],
        ];
    }

    /**
     * @dataProvider moveErrorsProvider
     *
     * @throws PropertyNotFoundException
     */
    public function testMoveErrors(
        string $spaceObject,
        array $coordinates,
        array $vector,
        string $expectedException
    ): void
    {
        $this->expectException($expectedException);
        $object = new $spaceObject();

        if (empty($coordinates) === false) {
            $position = new Coordinates(...$coordinates);
            $object->setProperty(MovablePropertyEnum::POSITION, $position);
        }

        $movableShip = new MovableAdapter($object);

        if (empty($vector) === false) {
            $direction = new Coordinates(...$vector);
            $movableShip->setDirection($direction);
        }

        $movableShip->moveSpaceObject();
    }

    public static function moveErrorsProvider(): array
    {
        return [
            'emptyPosition' => [
                'object' => DefaultShip::class,
                'coordinates' => [
                 ],
                'vector' => [
                    'x' => '1',
                    'y' => '1',
                ],
                'expectedException' => PropertyNotFoundException::class,
            ],
            'emptySpeed' => [
                'object' => DefaultShip::class,
                'coordinates' => [
                    'x' => '1',
                    'y' => '1',
                ],
                'vector' => [
                ],
                'expectedException' => PropertyNotFoundException::class,
            ],
            'notMovableObject' => [
                'object' => DefaultStaticObject::class,
                'coordinates' => [
                    'x' => '1',
                    'y' => '1',
                ],
                'vector' => [
                    'x' => '1',
                    'y' => '1',
                ],
                'expectedException' => TypeError::class,
            ],
        ];
    }
}
