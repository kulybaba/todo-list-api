<?php

namespace App\Controller\Api;

use App\Entity\Card;
use App\Entity\TodoList;
use App\Services\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentController extends AbstractController
{
    /**
     * @Route("/api/payments/list", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction(Request $request, $limit = 1)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        \Stripe\Stripe::setApiKey(getenv('SECRET_KEY'));
        $payments = \Stripe\Charge::all(["limit" => $request->query->get('limit')]);

        return $this->json($payments);
    }

    /**
     * @Route("/api/payments/todo-lists/{todoList<\d+>}/cards/{card<\d+>}/unblock", methods={"POST"})
     */
    public function createAction(Request $request, TodoList $todoList, Card $card, UserService $userService, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        if (!$todoList->getBlock()) {
            throw new HttpException('400', 'Todo list not blocked.');
        }

        $data = json_decode($request->getContent(), true);

        if (!array_key_exists('description', $data)) {
            throw new HttpException('400', 'Bad request');
        }

        \Stripe\Stripe::setApiKey(getenv('SECRET_KEY'));
        $cardToken = \Stripe\Token::retrieve($card->getCardToken());
        $charge = \Stripe\Charge::create([
            'amount' => 2000,
            'currency' => 'usd',
            'source' => $cardToken['card']['id'],
            'customer' => $this->getUser()->getCustomerId(),
            'description' => $data['description']
        ]);

        $todoList->setBlock(false);
        $todoList->setExpire(date('Y-m-d H:i:s', strtotime('+3 days')));
        $em = $this->getDoctrine()->getManager();
        $em->persist($todoList);
        $em->flush();

        $userService->sendUnblockTodoListEmail($this->getUser());

        return $this->json($charge);
    }
}
