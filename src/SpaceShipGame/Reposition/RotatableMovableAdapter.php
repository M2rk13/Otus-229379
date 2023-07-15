<?php

namespace App\SpaceShipGame\Reposition;

use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class RotatableMovableAdapter implements MovableInterface, RotatableInterface
{
    private const ANGULAR_TOTAL = 360;

    private DefaultMovableObject $object;
    private MovableInterface $movableAdapter;
    private RotatableInterface $rotatableAdapter;

    public function __construct(
        DefaultMovableObject $object,
        MovableInterface $movableAdapter,
        RotatableInterface $rotatableAdapter
    ) {
        $this->object = $object;
        $this->movableAdapter = $movableAdapter;
        $this->rotatableAdapter = $rotatableAdapter;
    }


    public function setDirection(Coordinates $newVector): void
    {
        $this->movableAdapter->setDirection($newVector);
    }

    public function moveSpaceObject(): void
    {
        /** @var Coordinates $position */
        /** @var Coordinates $direction */
        $position = $this->object->getProperty(MovablePropertyEnum::POSITION);
        $direction = $this->object->getProperty(MovablePropertyEnum::DIRECTION);
        $directionAngular = $this->object->getProperty(MovablePropertyEnum::ANGULAR_DIRECTION);

        $newDirection = new Coordinates(
            $direction->x * cos($directionAngular / self::ANGULAR_TOTAL),
            $direction->y * sin($directionAngular / self::ANGULAR_TOTAL)
        );

        $newPosition = new Coordinates(
            $position->x + $newDirection->x,
            $position-> y + $newDirection->y
        );

        $this->object->setProperty(MovablePropertyEnum::POSITION, $newPosition);
    }

    public function setAngular(int $directionAngular): void
    {
        $this->rotatableAdapter->setAngular($directionAngular);
    }

    public function rotateSpaceObject(): void
    {
        $this->rotatableAdapter->rotateSpaceObject();
    }
}