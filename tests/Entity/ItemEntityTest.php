<?php

namespace App\Tests\Entity;

use App\Entity\Item;
use PHPUnit\Framework\TestCase;

class ItemEntityTest extends TestCase
{
    public function testGetAndSetText()
    {
        $item = new Item();
        $item->setText('Task');
        $result = $item->getText();
        $this->assertEquals('Task', $result);
    }

    public function testGetAndSetCompleted()
    {
        $item = new Item();
        $item->setCompleted(true);
        $result = $item->getCompleted();
        $this->assertEquals(true, $result);
    }

    public function testGetAndSetPriority()
    {
        $item = new Item();
        $item->setPriority(1);
        $result = $item->getPriority();
        $this->assertEquals(1, $result);
    }
}
