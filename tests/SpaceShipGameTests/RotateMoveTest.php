<?php

declare(strict_types=1);

namespace App\Tests\SpaceShipGameTests;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\MacroCommand\RotateMoveCommand;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\Reposition\MovableAdapter;
use App\SpaceShipGame\Reposition\RotatableAdapter;
use App\SpaceShipGame\SpaceObjects\Ship\DefaultShip;
use PHPUnit\Framework\TestCase;

class RotateMoveTest extends TestCase
{
    private const TEST_FLOAT_SCALE = 11;

    /**
     * @dataProvider linearMoveDataProvider
     *
     * @throws PropertyNotFoundException
     * @throws AngularValueException
     */
    public function testRotateMove(
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

        $rotatableAdapter = new RotatableAdapter($ship);
        $movableAdapter = new MovableAdapter($ship);
        $rotatableAdapter->setAngular($rotateAngular);
        $movableAdapter->setDirection($direction);

        $command = new RotateMoveCommand($ship);
        $command->execute();

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
}
