<?php

declare(strict_types=1);

namespace App\SpaceShipGame\Command\SimpleCommand;

use App\Exception\MaxSpeedException;
use App\Exception\PropertyNotFoundException;
use App\SpaceShipGame\Command\CommandStatusEnum;
use App\SpaceShipGame\Command\DefaultCommand;
use App\SpaceShipGame\Reposition\ChangeableVelocityAdapter;
use App\SpaceShipGame\SpaceObjects\DefaultMovableObject;

class ChangeVelocityCommand extends DefaultCommand
{
    private DefaultMovableObject $object;
    private string $speed;

    public function __construct(DefaultMovableObject $object, string $speed)
    {
        $this->object = $object;
        $this->speed = $speed;
    }

    /**
     * @throws PropertyNotFoundException
     * @throws MaxSpeedException
     */
    public function execute(): int
    {
        $movableAdapter = new ChangeableVelocityAdapter($this->object);
        $movableAdapter->setSpeed($this->speed);

        return CommandStatusEnum::SUCCESS;
    }
}
