<?php


namespace App\Http\Controllers;

use App\Infrastructure\Interfaces\Session\SessionInterface;
use
    Psr\Http\Message\RequestInterface,
    Psr\Http\Message\ResponseInterface,
    Psr\Log\LoggerInterface,
    App\Infrastructure\Interfaces\View\ViewInterface,
    Slim\Flash\Messages as FlashMessages;

abstract class GenericController
{
    /**
     * @var ViewInterface
     */
    protected $view; 
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var FlashMessages
     */
    protected $flashMessages;

    /**
     * @var SessionInterface
     */
    protected $session;

    public function __construct(ViewInterface $view, LoggerInterface $logger, \PDO $db, SessionInterface $session, FlashMessages $flashMessages)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->db = $db;
        $this->flashMessages = $flashMessages;
        $this->session = $session;
    }

}