<?php


namespace App\Http\Controllers;

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

    public function __construct(ViewInterface $view, LoggerInterface $logger, \PDO $db, FlashMessages $flashMessages)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->db = $db;
        $this->flashMessages = $flashMessages;
    }

}