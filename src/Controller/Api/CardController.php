<?php

namespace App\Controller\Api;

use App\Entity\Card;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CardController extends AbstractController
{
    /**
     * @Route("/api/payments/user/{id<\d+>}/cards/create", methods={"POST"})
     */
    public function createAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, User $user)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $card = $serializer->deserialize($request->getContent(), Card::class, JsonEncoder::FORMAT);

        \Stripe\Stripe::setApiKey(getenv('SECRET_KEY'));
        $cardToken = \Stripe\Token::create([
            'card' => [
                'number' => $card->getNumber(),
                'exp_month' => $card->getExpMonth(),
                'exp_year' => $card->getExpYear(),
                'cvc' => $card->getCvc()
            ]
        ]);
        $customer = \Stripe\Customer::retrieve($user->getCustomerId());
        $customerCard = $customer->sources->create(["source" => $cardToken['id']]);
        $card->setUser($this->getUser());
        $card->setLast4($cardToken['card']['last4']);
        $card->setCardId($customerCard['id']);

        if (count($validator->validate($card))) {
            throw new HttpException('400', 'Bad request');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($card);
        $em->flush();

        return $this->json($card);
    }

    /**
     * @Route("/api/payments/user/{user<\d+>}/cards/{userCard<\d+>}/set-name", methods={"PUT"})
     */
    public function setNameAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, User $user, Card $userCard)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $serializer->deserialize($request->getContent(), Card::class, JsonEncoder::FORMAT, ['object_to_populate' => $userCard]);

        if (count($validator->validate($userCard, null, 'set_name'))) {
            throw new HttpException('400', 'Bad request');
        }

        \Stripe\Stripe::setApiKey(getenv('SECRET_KEY'));
        $customer = \Stripe\Customer::retrieve($user->getCustomerId());
        $card = $customer->sources->retrieve($userCard->getCardId());
        $card->name = $userCard->getName();
        $card->save();

        $em = $this->getDoctrine()->getManager();
        $em->persist($userCard);
        $em->flush();

        return $this->json($userCard);
    }

    /**
     * @Route("/api/payments/user/{user<\d+>}/cards/{card<\d+>}/delete", methods={"DELETE"})
     */
    public function deleteAction(User $user, Card $card)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        \Stripe\Stripe::setApiKey(getenv('SECRET_KEY'));
        $customer = \Stripe\Customer::retrieve($user->getCustomerId());
        $customer->sources->retrieve($card->getCardId())->delete();

        $em = $this->getDoctrine()->getManager();
        $em->remove($card);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Card deleted!'
        ],
        Response::HTTP_OK);
    }
}
