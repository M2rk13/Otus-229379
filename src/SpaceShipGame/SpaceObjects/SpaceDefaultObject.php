<?php

namespace App\SpaceShipGame\SpaceObjects;

use App\Exception\PropertyNotFoundException;

abstract class SpaceDefaultObject implements SpaceObjectInterface
{
    /**
     * @throws PropertyNotFoundException
     */
    public function getProperty(string $key)
    {
        if (property_exists($this, $key) === false) {
            throw new PropertyNotFoundException($key);
        }

        return $this->$key;
    }

    public function setProperty(string $key, $newValue): void
    {
        $this->$key = $newValue;
    }
}