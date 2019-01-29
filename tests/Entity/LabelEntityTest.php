<?php

namespace App\Tests\Entity;

use App\Entity\Label;
use PHPUnit\Framework\TestCase;

class LabelEntityTest extends TestCase
{
    public function testGetAndSetText()
    {
        $label = new Label();
        $label->setText('text');
        $result = $label->getText();
        $this->assertEquals('text', $result);
    }
}
