<?php

namespace Tests\Functional;

use GuzzleHttp;
use PHPUnit\Framework\TestCase;

class GenericHttpTestCase extends TestCase
{
    /**
     * @var GuzzleHttp\Client;
     */
    protected $httpClient;

    public function setUp()
    {
        parent::setUp();
        $this->httpClient = new GuzzleHttp\Client([
            'base_uri' => $this->getBaseUri(),
            'exceptions' => false,
        ]);

    }

    protected function getBaseUri()
    {
        return 'http://localhost:8080';
    }

}
