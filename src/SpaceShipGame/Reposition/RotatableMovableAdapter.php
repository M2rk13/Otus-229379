<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class RotatableMovableAdapter extends RepositionAdapter implements MovableInterface, RotatableInterface
{
    private const FLOAT_SCALE = 13;

    private DefaultMovableObject $object;
    private MovableAdapter $movableAdapter;
    private RotatableAdapter $rotatableAdapter;

    public function __construct(
        DefaultMovableObject $object,
    ) {
        $this->object = $object;
        $this->movableAdapter = new MovableAdapter($object);
        $this->rotatableAdapter = new RotatableAdapter($object);
    }


    public function setDirection(Coordinates $newVector): void
    {
        $this->movableAdapter->setDirection($newVector);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function moveSpaceObject(): void
    {
        /** @var Coordinates $position */
        /** @var Coordinates $direction */
        $position = $this->object->getProperty(MovablePropertyEnum::POSITION);
        $direction = $this->object->getProperty(MovablePropertyEnum::DIRECTION);
        $directionAngular = $this->object->getProperty(MovablePropertyEnum::ANGULAR_DIRECTION);

        bcscale(self::FLOAT_SCALE);

        $angularRadians = bcdiv(
            bcmul((string) M_PI, (string) $directionAngular),
            self::ANGULAR_TOTAL,
            self::FLOAT_SCALE
        );

        $newPosition = new Coordinates(
            bcadd($position->x, $this->getVectorDX($direction, $angularRadians)),
            bcadd($position->y, $this->getVectorDY($direction, $angularRadians))
        );

        $this->object->setProperty(MovablePropertyEnum::POSITION, $newPosition);
    }

    /**
     * @throws AngularValueException
     */
    public function setAngular(int $directionAngular): void
    {
        $this->rotatableAdapter->setAngular($directionAngular);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function rotateSpaceObject(): void
    {
        $this->rotatableAdapter->rotateSpaceObject();
    }

    private function getVectorDX(Coordinates $direction, string $angularRadians): string
    {
        $firstPart = bcmul(
            $direction->x,
            number_format(cos((float) $angularRadians), self::FLOAT_SCALE),
            self::FLOAT_SCALE
        );

        $secondPart = bcmul(
            $direction->y,
            number_format(sin((float) $angularRadians), self::FLOAT_SCALE),
            self::FLOAT_SCALE
        );

        return bcsub($firstPart, $secondPart);
    }

    private function getVectorDY(Coordinates $direction, string $angularRadians): string
    {
        $firstPart = bcmul(
            $direction->x,
            number_format(sin((float) $angularRadians), self::FLOAT_SCALE),
            self::FLOAT_SCALE
        );

        $secondPart = bcmul(
            $direction->y,
            number_format(cos((float) $angularRadians), self::FLOAT_SCALE),
            self::FLOAT_SCALE
        );

        return bcsub($firstPart, $secondPart);
    }
}