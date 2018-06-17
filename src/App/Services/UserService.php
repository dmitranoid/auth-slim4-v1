<?php

namespace App\Services;


use App\ReadModel\Interfaces\UserFinderInterface;

class UserService
{
    /**
     * @var UserFinderInterface
     */
    private $userFinder;

    /**
     * @var
     */
    private $entityManager;

    public function __construct(UserFinderInterface $userFinder)
    {
        $this->userFinder = $userFinder;
//        $this->entityManager = $entityManager;
    }

    public function finder(): UserFinderInterface
    {
        return $this->userFinder;
    }

    public function create($name)
    {

    }

    public function rename($id, $newName)
    {

    }

    public function activate($id)
    {

    }

    public function deactivate($id)
    {

    }
}
