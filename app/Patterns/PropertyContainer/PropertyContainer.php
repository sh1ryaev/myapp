<?php

namespace App\Patterns\PropertyContainer;

use mysql_xdevapi\Exception;

class PropertyContainer implements PropertyContainerInterface
{

    private $propContainer = [];

    function addProperty($propName, $value)
    {
        $this->propContainer[$propName]=$value;
    }

    function deleteProperty($propName)
    {
        unset($this->propContainer[$propName]);
    }

    function getProperty($propName)
    {
        return $this->propContainer[$propName] ?? null;
    }

    function setProperty($propName, $value)
    {
        if(!isset($this->propContainer[$propName])){
            throw new \Exception("Property [{$propName}] not found");
        }

        $this->propContainer[$propName]=$value;
    }
}
