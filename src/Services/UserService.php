<?php

namespace App\Services;

use App\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $passwordEncoder;

    private $mailer;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    public function generateApiToken()
    {
        return Uuid::uuid4()->toString();
    }

    public function encodePassword(User $user)
    {
        return $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
    }

    public function sendRegistrationEmail(User $user)
    {
        $message = (new \Swift_Message('Symfony Blog - Registration email'))
            ->setFrom('ahurtep@gmai.com')
            ->setTo($user->getEmail())
            ->setBody('Congratulations! ' . $user->getFirstName() . ' ' . $user->getLastName() . ', you are successfully registered. ' . 'Email: ' . $user->getEmail() . '. Password: ' . $user->getPlainPassword() . '.');
        $this->mailer->send($message);
    }
}
