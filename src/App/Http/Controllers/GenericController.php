<?php


namespace App\Http\Controllers;

use 
    Psr\Http\Message\RequestInterface,
    Psr\Http\Message\ResponseInterface,
    Psr\Log\LoggerInterface,
    App\Infrastructure\View\ViewInterface;

class GenericController
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

    public function __construct(ViewInterface $view, LoggerInterface $logger, \PDO $db)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->db = $db;
    }

}