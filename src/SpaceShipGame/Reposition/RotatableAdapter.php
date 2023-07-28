<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class RotatableAdapter extends RepositionAdapter implements RotatableInterface
{
    private DefaultMovableObject $object;

    public function __construct(
        DefaultMovableObject $object,
    ) {
        $this->object = $object;
    }

    /**
     * @throws AngularValueException
     */
    public function setAngular(int $directionAngular): void
    {
        if (abs($directionAngular) > self::ANGULAR_TOTAL) {
            throw new AngularValueException((string) $directionAngular);
        }

        $this->object->setProperty(MovablePropertyEnum::ANGULAR_DIRECTION, $directionAngular);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function rotateSpaceObject(): void
    {
        $directionAngular = $this->object->getProperty(MovablePropertyEnum::ANGULAR_DIRECTION);
        $positionAngular = $this->object->getProperty(MovablePropertyEnum::ANGULAR_POSITION);

        $newPositionAngular = (int) bcmod(
            (string) ($directionAngular + $positionAngular),
            bcmul(self::ANGULAR_TOTAL, '2')
        );

        $this->object->setProperty(MovablePropertyEnum::ANGULAR_POSITION, $newPositionAngular);
    }
}
