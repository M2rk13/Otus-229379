<?php

declare(strict_types=1);

namespace App\Tests\SpaceShipGameTests;

use App\Exception\AngularValueException;
use App\Exception\MaxSpeedException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\MacroCommand\ComplexMoveCommand;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\ChangeableVelocityAdapter;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\Reposition\MovableAdapter;
use App\SpaceShipGame\Reposition\RotatableAdapter;
use App\SpaceShipGame\SpaceObjects\Ship\DefaultShip;
use PHPUnit\Framework\TestCase;

class ComplexMoveTest extends TestCase
{
    private const TEST_FLOAT_SCALE = 10;

    /**
     * @dataProvider linearMoveDataProvider
     *
     * @throws AngularValueException
     * @throws PropertyNotFoundException
     * @throws MaxSpeedException
     */
    public function testComplexMove(array $totalPack): void
    {
        [
            'startCoordinates' => $startCoordinates,
            'vector' => $vector,
            'startAngular' => $startAngular,
            'rotateAngular' => $rotateAngular,
            'velocity' => $velocity,
            'maxVelocity' => $maxVelocity,
            'weight' => $weight,
            'expectedValue' => $expectedValue,

        ] = $totalPack;

        $position = new Coordinates(...$startCoordinates);
        $direction = new Coordinates(...$vector);

        $ship = new DefaultShip();
        $ship->setProperty(MovablePropertyEnum::POSITION, $position);
        $ship->setProperty(MovablePropertyEnum::ANGULAR_POSITION, $startAngular);
        $ship->setProperty(MovablePropertyEnum::MAX_VELOCITY, $maxVelocity);
        $ship->setProperty(MovablePropertyEnum::WEIGHT, $weight);

        $rotateAdapter = new RotatableAdapter($ship);
        $rotateAdapter->setAngular($rotateAngular);

        $velocityAdapter = new ChangeableVelocityAdapter($ship);
        $velocityAdapter->setSpeed($velocity);

        $movableAdapter = new MovableAdapter($ship);
        $movableAdapter->setDirection($direction);

        $complexMoveCommand = new ComplexMoveCommand($ship);
        $complexMoveCommand->execute();

        $newCoordinates = $ship->getProperty(MovablePropertyEnum::POSITION);

        self::assertEquals(
            0,
            bccomp($newCoordinates->x, $expectedValue['x'], self::TEST_FLOAT_SCALE),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->x, $expectedValue['x'])
        );
        self::assertEquals(
            0,
            bccomp($newCoordinates->y, $expectedValue['y'], self::TEST_FLOAT_SCALE),
            sprintf('value not equal: got [%s], expected [%s]', $newCoordinates->y, $expectedValue['y'])
        );
    }

    public static function linearMoveDataProvider(): array
    {
        return [
            'correctComplexMove' => [
                'totalPack' => [
                    'startCoordinates' => [
                        'x' => '0',
                        'y' => '0',
                    ],
                    'vector' => [
                        'x' => '10',
                        'y' => '0'
                    ],
                    'startAngular' => 0,
                    'rotateAngular' => 90,
                    'velocity' => '5',
                    'maxVelocity' => '10',
                    'weight' => '5',
                    'expectedValue' => [
                        'x' => '0.6081444015312',
                        'y' => '2.4391339704969',
                    ],
                ]
            ],
            'complexMoveNoRotate' => [
                'totalPack' => [
                    'startCoordinates' => [
                        'x' => '0',
                        'y' => '0',
                    ],
                    'vector' => [
                        'x' => '2',
                        'y' => '0'
                    ],
                    'startAngular' => 0,
                    'rotateAngular' => 0,
                    'velocity' => '10',
                    'maxVelocity' => '10',
                    'weight' => '5',
                    'expectedValue' => [
                        'x' => '10',
                        'y' => '0',
                    ],
                ]
            ],
        ];
    }
}
