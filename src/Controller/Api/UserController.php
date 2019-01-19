<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", methods={"POST"})
     */
    public function registrationAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserService $userService)
    {
        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request1');
        }

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        if (count($validator->validate($user))) {
            throw new HttpException('400', 'Bad request');
        }

        $user->setPassword($userService->encodePassword($user));
        $user->setApiToken($userService->generateApiToken());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json($user);
    }
}
