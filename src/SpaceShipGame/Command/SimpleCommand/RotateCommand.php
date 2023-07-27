<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command\SimpleCommand;

use App\Exception\AngularValueException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\CommandStatusEnum;
use App\SpaceShipGame\Command\DefaultCommand;
use App\SpaceShipGame\Reposition\RotatableAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class RotateCommand extends DefaultCommand
{
    private DefaultMovableObject $object;
    private int $angular;

    public function __construct(DefaultMovableObject $object, int $angular)
    {
        $this->object = $object;
        $this->angular = $angular;
    }

    /**
     * @throws PropertyNotFoundException
     * @throws AngularValueException
     */
    public function execute(): int
    {
        $movableAdapter = new RotatableAdapter($this->object);
        $movableAdapter->setAngular($this->angular);
        $movableAdapter->rotateSpaceObject();

        return CommandStatusEnum::SUCCESS;
    }
}
