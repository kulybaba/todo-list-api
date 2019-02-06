<?php

namespace App\Controller\Api;

use App\Entity\Item;
use App\Entity\TodoList;
use App\Services\TodoListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemController extends AbstractController
{
    /**
     * @Route("/api/todo-lists/{id<\d+>}/items/create", methods={"POST"})
     */
    public function createAction(Request $request, TodoList $todoList, SerializerInterface $serializer, ValidatorInterface $validator, TodoListService $todoListService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $todoListService->checkExpire($todoList);

        if ($todoList->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $item = $serializer->deserialize($request->getContent(), Item::class, JsonEncoder::FORMAT);
        $item->setTodoList($todoList);

        if (count($validator->validate($item))) {
            throw new HttpException('400', 'Bad request');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        return $this->json($item);
    }

    /**
     * @Route("/api/todo-lists/{todoList<\d+>}/items/{item<\d+>}/delete", methods={"DELETE"})
     */
    public function deleteAction(TodoList $todoList, Item $item, TodoListService $todoListService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $todoListService->checkExpire($todoList);

        if ($todoList->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        $todoList->removeItem($item);

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        return $this->json($todoList);
    }

    /**
     * @Route("/api/todo-lists/items/{item<\d+>}/update", methods={"PUT"})
     */
    public function updateAction(Request $request, Item $item, SerializerInterface $serializer, ValidatorInterface $validator, TodoListService $todoListService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $todoListService->checkExpire($item->getTodoList());

        if ($item->getTodoList()->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $data = json_decode($request->getContent(), true);

        if (array_key_exists('text', $data)) {
            $item->setText($data['text']);
        }

        if (array_key_exists('completed', $data)) {
            $item->setCompleted($data['completed']);
        }

        if (array_key_exists('priority', $data)) {
            $item->setPriority($data['priority']);
        }

        if (count($validator->validate($item))) {
            throw new HttpException('400', 'Bad request');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        return $this->json($item);
    }

    /**
     * @Route("/api/todo-lists/items/{id<\d+>}/check", methods={"PUT"})
     */
    public function checkAction(Item $item, TodoListService $todoListService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $todoListService->checkExpire($item->getTodoList());

        if ($item->getTodoList()->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        $item->setCompleted(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        return $this->json($item);
    }

    /**
     * @Route("/api/todo-lists/items/{id<\d+>}/uncheck", methods={"PUT"})
     */
    public function uncheckAction(Item $item, TodoListService $todoListService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $todoListService->checkExpire($item->getTodoList());

        if ($item->getTodoList()->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        $item->setCompleted(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($item);
        $em->flush();

        return $this->json($item);
    }
}
