<?php


namespace App\Services;

use App\Infrastructure\Interfaces\PasswordHasher\PasswordHasherInterface;
use App\ReadModel\Interfaces\ApplicationFinderInterface;
use App\ReadModel\Interfaces\UserFinderInterface;

class AuthService
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
        if (!$user) {
            return false;
        }
        $userApplications = $this->applicationFinder->forUser($user['id']);
        foreach ($userApplications as $app) {
            if ($application == $app['code'] && 'active' == $app['user_app_status']) {
                return true;
            }
        }
        return false;
    }
}