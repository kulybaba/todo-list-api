<?php

namespace App\Controller\Api;

use App\Entity\Label;
use App\Entity\TodoList;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TodoListController extends AbstractController
{
    /**
     * @Route("/api/todo-lists/list", methods={"GET"})
     */
    public function listAction(Request $request, PaginatorInterface $paginator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $query = $this->getDoctrine()->getRepository(TodoList::class)->findAllTodoListsQuery();

        return $this->json([
            'todoLists' => $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                5
            )
        ]);
    }

    /**
     * @Route("/api/todo-lists/{id<\d+>}/view", methods={"GET"})
     */
    public function viewAction(TodoList $todoList)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->json($todoList);
    }

    /**
     * @Route("/api/todo-lists/create", methods={"POST"})
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $todoList = $serializer->deserialize($request->getContent(), TodoList::class, JsonEncoder::FORMAT);
        $todoList->setUser($this->getUser());
        $todoList->setCreated(new \DateTime());
        $todoList->setUpdated(new \DateTime());

        if (count($validator->validate($todoList))) {
            throw new HttpException('400', 'Bad request');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($todoList);
        $em->flush();

        return $this->json($todoList);
    }

    /**
     * @Route("/api/todo-lists/{id<\d+>}/update", methods={"PUT"})
     */
    public function updateAction(Request $request, TodoList $todoList, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($todoList->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $data = json_decode($request->getContent(), true);

        if (array_key_exists('name', $data)) {
            $todoList->setName($data['name']);
        }

        if (array_key_exists('expire', $data)) {
            $todoList->setExpire($data['expire']);
         }

        $todoList->setUpdated(new \DateTime());

        if (count($validator->validate($todoList))) {
            throw new HttpException('400', 'Bad request');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($todoList);
        $em->flush();

        return $this->json($todoList);
    }

    /**
     * @Route("/api/todo-lists/{id<\d+>}/delete", methods={"DELETE"})
     */
    public function deleteAction(TodoList $todoList)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($todoList->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($todoList);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'TODO list deleted!'
        ],
            Response::HTTP_OK);
    }

    /**
     * @Route("/api/todo-lists/{todoList<\d+>}/lables/{label<\d+>}/add", methods={"POST"})
     */
    public function addLabelAction(TodoList $todoList, Label $label)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($todoList->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        $todoList->addLabel($label);

        $em = $this->getDoctrine()->getManager();
        $em->persist($todoList);
        $em->flush();

        return $this->json($todoList);
    }

    /**
     * @Route("/api/todo-lists/{todoList<\d+>}/lables/{label<\d+>}/remove", methods={"DELETE"})
     */
    public function removeLabelAction(TodoList $todoList, Label $label)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($todoList->getBlock()) {
            throw new HttpException('400', 'Pay $20 to unblock the list.');
        }

        $todoList->removeLabel($label);

        $em = $this->getDoctrine()->getManager();
        $em->persist($todoList);
        $em->flush();

        return $this->json($todoList);
    }
}
