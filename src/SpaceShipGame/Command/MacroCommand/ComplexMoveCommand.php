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
use App\SpaceShipGame\Enum\WeightClassEnum;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\Reposition\RepositionAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class ComplexMoveCommand extends DefaultCommand
{
    private DefaultMovableObject $object;

    public function __construct(
        DefaultMovableObject $object,
    ) {
        $this->object = $object;
    }

    /**
     * @throws AngularValueException
     * @throws PropertyNotFoundException
     */
    public function execute(): int
    {
        bcscale(self::DEFAULT_FLOAT_SCALE);

        $directionAngular = $this->object->getProperty(MovablePropertyEnum::ANGULAR_DIRECTION);

        $rotateEfficient = $this->getRotateEfficient();
        $rotateAngular = (int) bcdiv(bcmul((string) $directionAngular, $rotateEfficient), '1');

        if ($rotateAngular + 10 > $directionAngular) {
            $rotateAngular = $directionAngular;
        }

        $rotateCommand = new RotateCommand($this->object, $rotateAngular);
        $rotateCommand->execute();

        $newDirectionAngular = $directionAngular - $rotateAngular;
        $this->object->setProperty(MovablePropertyEnum::ANGULAR_DIRECTION, $newDirectionAngular);

        $angularRadians = bcdiv(
            bcmul((string) M_PI, (string) $rotateAngular),
            RepositionAdapter::ANGULAR_TOTAL,
            self::DEFAULT_FLOAT_SCALE
        );

        /** @var Coordinates $position */
        /** @var Coordinates $direction */
        $position = $this->object->getProperty(MovablePropertyEnum::POSITION);
        $direction = $this->object->getProperty(MovablePropertyEnum::DIRECTION);

        $directionLength = $this->getDirectionLength($position, $direction);
        $speed = $this->object->getProperty(MovablePropertyEnum::VELOCITY);

        $pathLengthCoefficient = bcdiv($speed, $directionLength);

        $newDirection = new Coordinates(
            bcmul($direction->x, $pathLengthCoefficient),
            bcmul($direction->y, $pathLengthCoefficient)
        );

        $speedEfficient = $this->getSpeedEfficient($rotateAngular);
        $dx = bcmul($speedEfficient, $this->getVectorDX($newDirection, $angularRadians));
        $dy = bcmul($speedEfficient, $this->getVectorDY($newDirection, $angularRadians));

        $moveCommand = new MoveCommand($this->object, new Coordinates($dx, $dy));
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
            number_format(cos((float) $angularRadians), self::DEFAULT_FLOAT_SCALE)
        );

        return bcsub($firstPart, $secondPart);
    }

    /**
     * @throws PropertyNotFoundException
     */
    private function getRotateEfficient(): string
    {
        $weightClass = $this->object->getProperty(MovablePropertyEnum::WEIGHT);
        $speed = $this->object->getProperty(MovablePropertyEnum::VELOCITY);
        $maxSpeed = $this->object->getProperty(MovablePropertyEnum::MAX_VELOCITY);
        $rotateAngular = $this->object->getProperty(MovablePropertyEnum::ANGULAR_DIRECTION);

        $weightCoefficient = bcmul(
            bcdiv($weightClass, bcadd($weightClass, WeightClassEnum::MAX_MOVABLE_WEIGHT)),
            '1.6'
        );
        $speedCoefficient = str_replace('-', '', bcdiv($speed, $maxSpeed));
        $rotateCoefficient = bcdiv(
            bcadd(RepositionAdapter::ANGULAR_TOTAL, (string) $rotateAngular),
            RepositionAdapter::ANGULAR_TOTAL
        );

        $resultCoefficient = bcmul(
            bcmul($weightCoefficient, $weightCoefficient),
            bcmul($speedCoefficient, $speedCoefficient)
        );


        $result = bcsub('1.05', bcmul($resultCoefficient, $rotateCoefficient));

        return bccomp($result, '1') === 1 ? '1' : $result;
    }


    /**
     * @throws PropertyNotFoundException
     */
    private function getSpeedEfficient(int $rotateAngular): string
    {
        if ($rotateAngular <= 15) {
            return '1';
        }

        $weightClass = $this->object->getProperty(MovablePropertyEnum::WEIGHT);

        $angleCoefficient = bcdiv((string) $rotateAngular, RepositionAdapter::ANGULAR_TOTAL);
        $weightCoefficient = bcdiv(
            WeightClassEnum::MAX_MOVABLE_WEIGHT,
            bcadd(WeightClassEnum::MAX_MOVABLE_WEIGHT, $weightClass)
        );

        $result = bcmul(bcsub('1.1', bcmul($angleCoefficient, $angleCoefficient)), $weightCoefficient);

        return bccomp($result, '1') === 1 ? '1' : $result;
    }

    private function getDirectionLength(Coordinates $position, Coordinates $direction): string
    {
        $dx = bcsub($direction->x, $position->x);
        $dy = bcsub($direction->y, $position->y);

        return bcsqrt(bcadd(
            bcmul($dx, $dx),
            bcmul($dy, $dy),
        ));
    }
}
