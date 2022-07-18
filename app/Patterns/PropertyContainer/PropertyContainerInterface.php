<?php

namespace App\Patterns\PropertyContainer;

interface PropertyContainerInterface
{
    function addProperty($propName, $value);

    function deleteProperty($propName);

    function getProperty($propName);

    function setProperty($propName, $value);
}
