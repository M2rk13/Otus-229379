<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command\SimpleCommand;

use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\CommandStatusEnum;
use App\SpaceShipGame\Command\DefaultCommand;
use App\SpaceShipGame\Reposition\Coordinates;
use App\SpaceShipGame\Reposition\MovableAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class MoveCommand extends DefaultCommand
{
    private DefaultMovableObject $object;
    private Coordinates $direction;

    public function __construct(DefaultMovableObject $object, Coordinates $direction)
    {
        $this->object = $object;
        $this->direction = $direction;
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function execute(): int
    {
        $movableAdapter = new MovableAdapter($this->object);
        $movableAdapter->setDirection($this->direction);
        $movableAdapter->moveSpaceObject();

        return CommandStatusEnum::SUCCESS;
    }
}
