<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Reposition;

use App\Exception\MaxSpeedException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Enum\MovablePropertyEnum;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class ChangeableVelocityAdapter extends RepositionAdapter implements ChangeableVelocityInterface
{
    private DefaultMovableObject $object;

    public function __construct(
        DefaultMovableObject $object,
    ) {
        $this->object = $object;
    }

    /**
     * @throws MaxSpeedException
     * @throws PropertyNotFoundException
     */
    public function setSpeed(string $speed): void
    {
        $maxSpeed = $this->object->getProperty(MovablePropertyEnum::MAX_VELOCITY);

        if ($speed > $maxSpeed) {
            throw new MaxSpeedException(sprintf('max [%s], received [%s]', $maxSpeed, $speed));
        }

        $this->object->setProperty(MovablePropertyEnum::VELOCITY, $speed);
    }
}
