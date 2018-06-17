<?php

namespace App\ReadModel\Interfaces;


interface GenericFinderInterface
{
    function __construct(\PDO $db);
}