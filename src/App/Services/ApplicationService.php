<?php


namespace App\Services;


use App\Infrastructure\Interfaces\PasswordHasher\PasswordHasherInterface;
use App\ReadModel\Interfaces\ApplicationFinderInterface;
use App\ReadModel\Interfaces\UserFinderInterface;

class ApplicationService
{
    /**
     * @var UserFinderInterface
     */
    private $userFinder;
    /**
     * @var ApplicationFinderInterface
     */
    private $applicationFinder;

    /**
     * @var PasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(
        UserFinderInterface $userFinder,
        ApplicationFinderInterface $applicationFinder,
        PasswordHasherInterface $hasher
    ) {
        $this->userFinder = $userFinder;
        $this->applicationFinder = $applicationFinder;
        $this->passwordHasher = $hasher;
    }

    public function checkUserLogin($name, $password)
    {
        $user = $this->userFinder->byName($name);
        if (!$user) {
            return false;
        }
        return $this->passwordHasher->check($password, $user['password']);
    }

    public function checkUserPermissionForApp($username, $application)
    {
        $user = $this->userFinder->byName($username);
        $application = $this->applicationFinder->byCode($application);

    }
}