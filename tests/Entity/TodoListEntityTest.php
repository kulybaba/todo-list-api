<?php

namespace App\Tests\Entity;

use App\Entity\TodoList;
use PHPUnit\Framework\TestCase;

class TodoListEntityTest extends TestCase
{
    public function testGetAndSetName()
    {
        $todoList = new TodoList();
        $todoList->setName('Home work');
        $result = $todoList->getName();
        $this->assertEquals('Home work', $result);
    }

    public function testGetAndSetExpire()
    {
        $todoList = new TodoList();
        $todoList->setExpire('2019-01-30 22:22:22');
        $result = $todoList->getExpire();
        $this->assertEquals('2019-01-30 22:22:22', $result);
    }

    public function testGetAndSetCreated()
    {
        $date = new \DateTime();

        $todoList = new TodoList();
        $todoList->setCreated($date);
        $result = $todoList->getCreated();
        $this->assertEquals($date, $result);
    }

    public function testGetAndSetUpdated()
    {
        $date = new \DateTime();

        $todoList = new TodoList();
        $todoList->setUpdated($date);
        $result = $todoList->getUpdated();
        $this->assertEquals($date, $result);
    }
}
