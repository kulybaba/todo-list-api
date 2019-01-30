<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LabelControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('GET', '/api/labels/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('labels', $data);
    }

    public function testViewAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('GET', "/api/labels/{$id}/view");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('text', $data);
    }

    public function testCreateAction()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'text' => 'label'
        ];

        $crawler = $client->request('POST', "/api/labels/create", [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertEquals('label', $data['text']);
    }

    public function testUpdateAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'text' => 'label'
        ];

        $crawler = $client->request('PUT', "/api/labels/{$id}/update", [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertEquals($id, $data['id']);

        $this->assertEquals('label', $data['text']);
    }

    public function testDeleteAction($id = 12)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('DELETE', "/api/labels/{$id}/delete");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('success', $data);

        $this->assertArrayHasKey('message', $data);

        $this->assertContains(true, $data);

        $this->assertContains('Label deleted!', $data);
    }
}
