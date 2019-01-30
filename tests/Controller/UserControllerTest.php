<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegistrationAction()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'firstName' => 'Test',
            'lastName' => 'Test',
            'email' => 'test@mail.com',
            'plainPassword' => '111111'
        ];

        $crawler = $client->request('POST', "/api/registration", [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('firstName', $data);

        $this->assertArrayHasKey('lastName', $data);

        $this->assertArrayHasKey('role', $data);

        $this->assertArrayHasKey('email', $data);

        $this->assertArrayHasKey('password', $data);

        $this->assertArrayHasKey('api_token', $data);

        $this->assertEquals('Test', $data['firstName']);

        $this->assertEquals('Test', $data['lastName']);

        $this->assertEquals('test@mail.com', $data['email']);
    }

    public function testLoginAction()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'email' => 'test@mail.com',
            'password' => '111111'
        ];

        $crawler = $client->request('POST', "/api/login", [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('firstName', $data);

        $this->assertArrayHasKey('lastName', $data);

        $this->assertArrayHasKey('role', $data);

        $this->assertArrayHasKey('email', $data);

        $this->assertArrayHasKey('password', $data);

        $this->assertArrayHasKey('api_token', $data);

        $this->assertEquals('test@mail.com', $data['email']);
    }
}
