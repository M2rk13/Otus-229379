<?php

namespace App\SpaceShipGame\Reposition;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class RotatableAdapter implements RotatableInterface
{
    private const ANGULAR_TOTAL = 180;
    private const ANGULAR_STEP = 45;

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
            throw new AngularValueException($directionAngular);
        }

        $newDirectionAngular = (int) round($directionAngular / self::ANGULAR_STEP) * self::ANGULAR_STEP;

        if ($newDirectionAngular !== $directionAngular) {
            echo sprintf(
                'MIN step is %s. Got "Angular": %s',
                self::ANGULAR_STEP,
                $newDirectionAngular
            );
        }

        $this->object->setProperty(MovablePropertyEnum::ANGULAR_DIRECTION, $newDirectionAngular);
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
            (string) self::ANGULAR_TOTAL
        );

        $this->object->setProperty(MovablePropertyEnum::ANGULAR_POSITION, $newPositionAngular);
    }
}