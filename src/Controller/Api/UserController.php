<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/registration", methods={"POST"})
     */
    public function registrationAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserService $userService)
    {
        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $user = $serializer->deserialize($request->getContent(), User::class, JsonEncoder::FORMAT);

        if (count($validator->validate($user))) {
            throw new HttpException('400', 'Bad request');
        }

        $user->setPassword($userService->encodePassword($user));
        $user->setApiToken($userService->generateApiToken());

        $customer = $userService->createCustomer($user);

        $user->setCustomerId($customer['id']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $userService->sendRegistrationEmail($user);

        return $this->json($user);
    }

    /**
     * @Route("/api/login", methods={"POST"})
     */
    public function loginAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserService $userService, SerializerInterface $serializer)
    {
        if (!$request->getContent()) {
            throw new HttpException('400', 'Bad request');
        }

        $data = $serializer->deserialize($request->getContent(), User::class, JsonEncoder::FORMAT);

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);

        if ($user instanceof User) {
            if ($passwordEncoder->isPasswordValid($user, $data->getPassword())) {
                $user->setApiToken($userService->generateApiToken());
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->json($user);
            }
        }

        throw new HttpException('400', 'Bad request');
    }
}
