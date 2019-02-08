<?php

namespace App\Services;

use App\Entity\TodoList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoListService extends AbstractController
{
    private $em;

    private $userService;

    public function __construct(EntityManagerInterface $em, UserService $userService)
    {
        $this->em = $em;
        $this->userService = $userService;
    }

    public function checkExpire(TodoList $todoList)
    {
        if ($todoList->getExpire() < date('Y-m-d H:i:s')) {
            $this->blockList($todoList);
        }
    }

    public function blockList(TodoList $todoList)
    {
        $todoList->setBlock(true);
        $this->em->persist($todoList);
        $this->em->flush();
    }

    public function checkDayBeforeExpire()
    {
        $todoLists = $this->em->getRepository(TodoList::class)->findAll();

        foreach ($todoLists as $todoList) {
            if (date('Y-m-d', strtotime('+1 day')) == $todoList->getExpire()) {
                $this->userService->sendWarningBlockTodoListEmail($todoList->getUser());
            }
        }
    }
}
