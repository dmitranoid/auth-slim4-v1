<?php

namespace App\Infrastructure\Repository;


use App\Infrastructure\Hydrator;

class PDORepositoryFactory
{
    /**
     *
     * @var \PDO $db
     */
    protected $db;
    
    protected $hydrator;

    public function __construct(\PDO $db, HydratorInterface $hydrator){
        $this->db = $db;
        $this->hydrator = $hydrator;
    }

    public function getRepository($entityName){

        $fullClassname = "App\Infrastructure\Repository\PDO\\{$entityName}Repository";
        return new $fullClassname($this->db, $this->hydrator);
    }
}