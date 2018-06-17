<?php
/**
 * User: shl
 * Date: 17.06.2018
 * Time: 23:15
 */

namespace App\Api\Controllers;


use App\ReadModel\PdoFinder\Application\PdoApplicationFinder;
use App\ReadModel\PdoFinder\Ticket\PdoTicketFinder;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\TicketService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class TicketController
{
    /**
     * @var LoggerInterface
     */
    var $logger;

    /**
     * @var \PDO
     */
    var $db;


    public function __construct(LoggerInterface $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }

    public function check(ServerRequestInterface $request, ResponseInterface $response, $args)
    {

        $ticketService = new TicketService(
            new PdoTicketFinder($this->db),
            new PdoUserFinder($this->db),
            new PdoApplicationFinder($this->db)
        );
        $ticket = $ticketService->checkTicketByNo();
        $data = [];
        return $response->withJson($data);
    }
}