<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command\MacroCommand;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\CommandStatusEnum;
use App\SpaceShipGame\Command\DefaultCommand;
use App\SpaceShipGame\Command\SimpleCommand\MoveCommand;
use App\SpaceShipGame\Command\SimpleCommand\RotateCommand;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\Reposition\RepositionAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class RotateMoveCommand extends DefaultCommand
{
    private DefaultMovableObject $object;

    public function __construct(
        DefaultMovableObject $object,
    ) {
        $this->object = $object;
    }

    /**
     * @throws PropertyNotFoundException
     * @throws AngularValueException
     */
    public function execute(): int
    {
        bcscale(self::DEFAULT_FLOAT_SCALE);

        /** @var Coordinates $direction */
        $direction = $this->object->getProperty(MovablePropertyEnum::DIRECTION);
        $directionAngular = $this->object->getProperty(MovablePropertyEnum::ANGULAR_DIRECTION);

        $angularRadians = bcdiv(
            bcmul((string) M_PI, (string) $directionAngular),
            RepositionAdapter::ANGULAR_TOTAL,
        );

        $newDirection = new Coordinates(
            $this->getVectorDX($direction, $angularRadians),
            $this->getVectorDY($direction, $angularRadians)
        );

        $rotateCommand = new RotateCommand($this->object, $directionAngular);
        $moveCommand = new MoveCommand($this->object, $newDirection);

        $rotateCommand->execute();
        $moveCommand->execute();

        return CommandStatusEnum::SUCCESS;
    }

    private function getVectorDX(Coordinates $direction, string $angularRadians): string
    {
        $firstPart = bcmul(
            $direction->x,
            number_format(cos((float) $angularRadians), self::DEFAULT_FLOAT_SCALE)
        );

        $secondPart = bcmul(
            $direction->y,
            number_format(sin((float) $angularRadians), self::DEFAULT_FLOAT_SCALE)
        );

        return bcsub($firstPart, $secondPart);
    }

    private function getVectorDY(Coordinates $direction, string $angularRadians): string
    {
        $firstPart = bcmul(
            $direction->x,
            number_format(sin((float) $angularRadians), self::DEFAULT_FLOAT_SCALE)
        );

        $secondPart = bcmul(
            $direction->y,
            number_format(cos((float) $angularRadians), self::DEFAULT_FLOAT_SCALE),
        );

        return bcsub($firstPart, $secondPart);
    }
}
