<?php

declare(strict_types=1);

namespace App\Tests\SpaceShipGameTests;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\Reposition\RotatableMovableAdapter;
use App\SpaceShipGame\SpaceObjects\Ship\DefaultShip;
use PHPUnit\Framework\TestCase;

class ComplexMoveTest extends TestCase
{
    private const TEST_FLOAT_SCALE = 11;

    /**
     * @dataProvider linearMoveDataProvider
     *
     * @throws PropertyNotFoundException
     * @throws AngularValueException
     */
    public function testComplexMove(
        array $startCoordinates,
        array $vector,
        int $startAngular,
        int $rotateAngular,
        array $expectedValue
    ): void
    {
        $position = new Coordinates(...$startCoordinates);
        $direction = new Coordinates(...$vector);

        $ship = new DefaultShip();
        $ship->setProperty(MovablePropertyEnum::POSITION, $position);
        $ship->setProperty(MovablePropertyEnum::ANGULAR_POSITION, $startAngular);

        $rotatableMovableAdapter = new RotatableMovableAdapter($ship);

        $rotatableMovableAdapter->setAngular($rotateAngular);
        $rotatableMovableAdapter->setDirection($direction);
        $rotatableMovableAdapter->rotateSpaceObject();

        $rotatableMovableAdapter->moveSpaceObject();

        $newCoordinates = $ship->getProperty(MovablePropertyEnum::POSITION);

        self::assertEquals(
            0,
            bccomp($newCoordinates->x, $expectedValue['x'], self::TEST_FLOAT_SCALE),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->x, $expectedValue['x'])
        );
        self::assertEquals(
            0,
            bccomp($newCoordinates->y, $expectedValue['y'], self::TEST_FLOAT_SCALE),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->x, $expectedValue['x'])
        );
    }

    public static function linearMoveDataProvider(): array
    {
        return [
            'correctComplexMove' => [
                'startCoordinates' => [
                    'x' => '0',
                    'y' => '0',
                ],
                'vector' => [
                    'x' => '5',
                    'y' => '0'
                ],
                'startAngular' => 0,
                'rotateAngular' => 45,
                'expectedValue' => [
                    'x' => '3.53553390593',
                    'y' => '3.53553390593',
                ],
            ],
        ];
    }
//
//    /**
//     * @dataProvider moveErrorsProvider
//     *
//     * @throws PropertyNotFoundException
//     */
//    public function testMoveErrors(
//        string $spaceObject,
//        array $coordinates,
//        array $vector,
//        string $expectedException
//    ): void
//    {
//        $this->expectException($expectedException);
//        $object = new $spaceObject();
//
//        if (empty($coordinates) === false) {
//            $position = new Coordinates(...$coordinates);
//            $object->setProperty(MovablePropertyEnum::POSITION, $position);
//        }
//
//        $movableShip = new MovableAdapter($object);
//
//        if (empty($vector) === false) {
//            $direction = new Coordinates(...$vector);
//            $movableShip->setDirection($direction);
//        }
//
//        $movableShip->moveSpaceObject();
//    }
//
//    public static function moveErrorsProvider(): array
//    {
//        return [
//            'emptyPosition' => [
//                'object' => DefaultShip::class,
//                'coordinates' => [
//                ],
//                'vector' => [
//                    'x' => 1,
//                    'y' => 1,
//                ],
//                'expectedException' => PropertyNotFoundException::class,
//            ],
//            'emptySpeed' => [
//                'object' => DefaultShip::class,
//                'coordinates' => [
//                    'x' => 1,
//                    'y' => 1,
//                ],
//                'vector' => [
//                ],
//                'expectedException' => PropertyNotFoundException::class,
//            ],
//            'notMovableObject' => [
//                'object' => DefaultStaticObject::class,
//                'coordinates' => [
//                    'x' => 1,
//                    'y' => 1,
//                ],
//                'vector' => [
//                    'x' => 1,
//                    'y' => 1,
//                ],
//                'expectedException' => TypeError::class,
//            ],
//        ];
//    }
}