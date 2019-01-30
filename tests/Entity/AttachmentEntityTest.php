<?php

namespace App\Tests\Entity;

use App\Entity\Attachment;
use PHPUnit\Framework\TestCase;

class AttachmentEntityTest extends TestCase
{
    public function testGetAndSetType()
    {
        $attachment = new Attachment();
        $attachment->setType('video');
        $result = $attachment->getType();
        $this->assertEquals('video', $result);
    }

    public function testGetAndSetSrc()
    {
        $attachment = new Attachment();
        $attachment->setSrc('/uploads/video/video.mp4');
        $result = $attachment->getSrc();
        $this->assertEquals('/uploads/video/video.mp4', $result);
    }
}
