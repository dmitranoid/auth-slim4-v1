<?php

namespace App\Readmodel\PdoFinder;

use Envms\FluentPDO\Query,
    App\ReadModel\Interfaces\GenericFinderInterface;


abstract class PdoGenericFinder implements GenericFinderInterface
{

    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var Query
     */
    protected $fquery;

    public function __construct(\PDO $db) {
        $this->db = $db;
        $this->fquery = new Query($this->db, $this->getFinderStructure());
    }

    protected function getFinderStructure()
    {
        return null;
    }
}