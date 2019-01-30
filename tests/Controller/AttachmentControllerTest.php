<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AttachmentControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('GET', '/api/attachments/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('attachments', $data);
    }

    public function testViewAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('GET', "/api/attachments/{$id}/view");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('type', $data);

        $this->assertArrayHasKey('src', $data);

        $this->assertEquals($id, $data['id']);
    }

    public function testCreateAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'type' => 'video',
            'src' => '/uploads/video/video.mp4'
        ];

        $crawler = $client->request('POST', "/api/todo-lists/items/{$id}/attachments/create", [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('completed', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertArrayHasKey('attachment', $data);

        $this->assertArrayHasKey('todoList', $data);

        $this->assertArrayHasKey('priority', $data);

        $this->assertEquals($id, $data['id']);

        $this->assertEquals('video', $data['type']);

        $this->assertEquals('/uploads/video/video.mp4', $data['src']);
    }

    public function testDeleteAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('DELETE', "/api/todo-lists/items/{$id}/attachments/delete");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('completed', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertArrayHasKey('attachment', $data);

        $this->assertArrayHasKey('todoList', $data);

        $this->assertArrayHasKey('priority', $data);

        $this->assertEquals(null, $data['attachment']);
    }
}
