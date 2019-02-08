<?php

namespace App\Services;

use App\Entity\TodoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoListService extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function checkExpire(TodoList $todoList)
    {
        if ($todoList->getExpire() < date('Y-m-d')) {
            $this->blockList($todoList);
        }
    }

    public function blockList(TodoList $todoList)
    {
        $todoList->setBlock(true);
        $this->em->persist($todoList);
        $this->em->flush();
    }
}
