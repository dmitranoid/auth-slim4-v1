<?php
/**
 * Created by PhpStorm.
 * User: svt3
 * Date: 19.06.2018
 * Time: 8:28
 */

namespace Tests\Unit\Services;

use App\ReadModel\PdoFinder\Application\PdoApplicationFinder;
use App\ReadModel\PdoFinder\Ticket\PdoTicketFinder;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\JwtService;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use PHPUnit\Framework\TestCase;
use Tests\Unit\TestHelpers\PdoUseTrait;


class JwtServiceTest extends TestCase
{
    use PdoUseTrait;

    /**
     * @var  JwtService
     */
    private $jwtService;

    /**
     * @var string $secretKey
     */
    private $secretKey;

    public function setUp()
    {

        $this->secretKey = '12345678';
        $db = $this->getRealPdoDb();

        $this->jwtService = new JwtService(
            new PdoTicketFinder($db),
            new PdoUserFinder($db),
            new PdoApplicationFinder($db),
            $this->secretKey
        );
    }


    public function testGenerateTokenByUserIdAppId()
    {
        $userId = 1;
        $appId = 1;
        $jwtToken = $this->jwtService->generateToken($userId, $appId);

        $decodedToken = JWT::decode($jwtToken, $this->secretKey, ['HS256']);

        $expectedData = [
            'userId'=>1,
            'appId'=>1,
            'iss'=>getenv('SITE_URL'),
        ];

        $actualData = [
            'userId'=> $decodedToken->userId,
            'appId' => $decodedToken->appId,
            'iss' => $decodedToken->iss,
        ];

        $this->assertEquals($expectedData, $actualData);
    }


    public function testTryHackToken()
    {
        $userId = 1;
        $appId = 1;
        $jwtToken = $this->jwtService->generateToken($userId, $appId);

        $jwtToken .= '1';

        $this->expectException(SignatureInvalidException::class);
        $decodedToken = JWT::decode($jwtToken, $this->secretKey, ['HS256']);
    }

    public function testValidateToken()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VySWQiOiIxIiwiYXBwSWQiOiIxIiwiaXNzIjoibG9jYWxob3N0OjgwODAiLCJpYXQiOjE1MzA1NTU3NzUsImV4cCI6MTUzMDY0MjE3NX0.229GSiRuv4rdB9iiBiwCjfH6fkpIWBbTLlrKuYf4mD0';
        $result = $this->jwtService->validateToken($token);
        var_dump($result);
        $this->assertInstanceOf(\stdClass::class, $result, 'token not valid');
    }


}
