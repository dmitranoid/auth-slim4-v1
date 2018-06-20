<?php

namespace Tests\Functional\Front\Login;


use Tests\Functional\GenericHttpTestCase;

class LoginTest extends GenericHttpTestCase
{
    public function testGetHomePageRedirectToLogin()
    {
        $response = $this->httpClient->get('/');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetLoginPage()
    {
        $response = $this->httpClient->get('/');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('войти', $response->getBody()->getContents());
    }

    public function testPostLoginPage()
    {
        $formData = [
            'application' => 'testapp',
            'username' => 'dummyuser',
            'password' => 'dummypassword',
            'submit' => 'login'
        ];
        $response = $this->httpClient->post('/login', ['form_params' => $formData]);
        $this->assertEquals(200, $response->getStatusCode());

    }

}
