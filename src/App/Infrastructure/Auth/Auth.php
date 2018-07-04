<?php


namespace App\Infrastructure\Auth;

use App\Infrastructure\Interfaces\Session\SessionInterface;
use App\Infrastructure\Interfaces\PasswordHasher\PasswordHasherInterface;
use App\ReadModel\Interfaces\ApplicationFinderInterface;

use App\ReadModel\Interfaces\UserFinderInterface;

class Auth implements \App\Infrastructure\Interfaces\Auth\AuthInterface
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

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        UserFinderInterface $userFinder,
        ApplicationFinderInterface $applicationFinder,
        PasswordHasherInterface $hasher,
        SessionInterface $session
    ) {
        $this->userFinder = $userFinder;
        $this->applicationFinder = $applicationFinder;
        $this->passwordHasher = $hasher;
        $this->session = $session;
    }

    public function login($username, $password)
    {
        /*
        if ($this->session->get('user', false)) {
            return true;
        }
        */
        $user = $this->userFinder->byName($username);
        if ($user && $this->passwordHasher->check($password, $user['password'])) {
            $this->session->set('user', $user);
            return true;
        }
        return false;
    }

    public function logout()
    {
        $this->session->remove('user');
    }

    function isLoggedIn(): bool
    {
        return (bool)$this->session->exist('user');
    }

    function getCurrentUser()
    {
        $user = $this->session->get('user');
        return $user;
    }


    /*
        public function checkUserLogin($name, $password)
        {
            $user = $this->userFinder->byName($name);
            if (!$user) {
                return false;
            }
            return $this->passwordHasher->check($password, $user['password']);
        }
    */

    /**
     * @param string $username имя пользователя
     * @param string $application Код приложения
     * @return bool
     */
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