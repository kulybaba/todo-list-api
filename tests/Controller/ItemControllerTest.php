<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemControllerTest extends WebTestCase
{
    public function testCreateAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'text' => 'Task3',
            'priority' => 3
        ];

        $crawler = $client->request('POST', "/api/todo-lists/{$id}/items/create", [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('completed', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertArrayHasKey('attachment', $data);

        $this->assertArrayHasKey('todoList', $data);

        $this->assertArrayHasKey('priority', $data);

        $this->assertEquals($id, $data['todoList']);

        $this->assertEquals('Task3', $data['text']);

        $this->assertEquals(3, $data['priority']);
    }

    public function testUpdateAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'text' => 'Task4',
            'completed' => true,
            'priority' => 4
        ];

        $crawler = $client->request('PUT', "/api/todo-lists/items/{$id}/update", [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('completed', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertArrayHasKey('attachment', $data);

        $this->assertArrayHasKey('todoList', $data);

        $this->assertArrayHasKey('priority', $data);

        $this->assertEquals($id, $data['id']);

        $this->assertEquals('Task4', $data['text']);

        $this->assertEquals(true, $data['completed']);

        $this->assertEquals(4, $data['priority']);
    }

    public function testDeleteAction($todoListId = 1, $itemId = 22)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('DELETE', "/api/todo-lists/{$todoListId}/items/{$itemId}/delete");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('name', $data);

        $this->assertArrayHasKey('expire', $data);

        $this->assertArrayHasKey('user', $data);

        $this->assertArrayHasKey('labels', $data);

        $this->assertArrayHasKey('items', $data);

        $this->assertArrayHasKey('created', $data);

        $this->assertArrayHasKey('updated', $data);

        $this->assertEquals($todoListId, $data['id']);
    }

    public function testCheckAction($id = 14)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('PUT', "/api/todo-lists/items/{$id}/check");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('completed', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertArrayHasKey('attachment', $data);

        $this->assertArrayHasKey('todoList', $data);

        $this->assertArrayHasKey('priority', $data);

        $this->assertEquals($id, $data['id']);

        $this->assertEquals(true, $data['completed']);
    }

    public function testUncheckAction($id = 14)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('PUT', "/api/todo-lists/items/{$id}/uncheck");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);

        $this->assertArrayHasKey('completed', $data);

        $this->assertArrayHasKey('text', $data);

        $this->assertArrayHasKey('attachment', $data);

        $this->assertArrayHasKey('todoList', $data);

        $this->assertArrayHasKey('priority', $data);

        $this->assertEquals($id, $data['id']);

        $this->assertEquals(false, $data['completed']);
    }
}
