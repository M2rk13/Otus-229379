<?php

namespace App\SpaceShipGame\Reposition;

use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class MovableAdapter implements MovableInterface
{
    private DefaultMovableObject $object;

    public function __construct(
        DefaultMovableObject $object,
    ) {
        $this->object = $object;
    }

    public function setDirection(Coordinates $newVector): void
    {
        $this->object->setProperty(MovablePropertyEnum::DIRECTION, $newVector);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function moveSpaceObject(): void
    {
        /** @var Coordinates $direction */
        /** @var Coordinates $position */
        $direction = $this->object->getProperty(MovablePropertyEnum::DIRECTION);
        $position = $this->object->getProperty(MovablePropertyEnum::POSITION);

        $newPosition = new Coordinates(
            $position->x + $direction->x,
            $position->y + $direction->y,
        );

        $this->object->setProperty(MovablePropertyEnum::POSITION, $newPosition);
    }
}