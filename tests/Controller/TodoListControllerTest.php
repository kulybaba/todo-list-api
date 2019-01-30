<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoListControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('GET', '/api/todo-lists/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('todoLists', $data);
    }

    public function testViewAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('GET', "/api/todo-lists/{$id}/view");

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

        $this->assertEquals($id, $data['id']);
    }

    public function testCreateAction()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'name' => 'Task list',
            'expire' => '2019-03-12 13:37:27'
        ];

        $crawler = $client->request('POST', "/api/todo-lists/create", [], [], [], json_encode($data));

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

        $this->assertEquals('Task list', $data['name']);

        $this->assertEquals('2019-03-12 13:37:27', $data['expire']);
    }

    public function testUpdateAction($id = 1)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $data = [
            'name' => 'Task list',
            'expire' => '2019-03-12 13:37:27'
        ];

        $crawler = $client->request('PUT', "/api/todo-lists/{$id}/update", [], [], [], json_encode($data));

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

        $this->assertEquals('Task list', $data['name']);

        $this->assertEquals('2019-03-12 13:37:27', $data['expire']);
    }

    public function testDeleteAction($id = 6)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('DELETE', "/api/todo-lists/{$id}/delete");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('success', $data);

        $this->assertArrayHasKey('message', $data);

        $this->assertContains(true, $data);

        $this->assertContains('TODO list deleted!', $data);
    }

    public function testAddLabelAction($todoListId = 2, $labelId = 2)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('POST', "/api/todo-lists/{$todoListId}/lables/{$labelId}/add");

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
    }

    public function testRemoveLabelAction($todoListId = 2, $labelId = 2)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'test@mail.com',
            'PHP_AUTH_PW'   => '111111',
        ]);

        $crawler = $client->request('DELETE', "/api/todo-lists/{$todoListId}/lables/{$labelId}/remove");

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
    }
}
