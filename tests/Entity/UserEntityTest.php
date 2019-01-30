<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    public function testGetAndSetFistName()
    {
        $user = new User();
        $user->setFirstName('Peter');
        $result = $user->getFirstName();
        $this->assertEquals('Peter', $result);
    }

    public function testGetAndSetLastName()
    {
        $user = new User();
        $user->setLastName('Parker');
        $result = $user->getLastName();
        $this->assertEquals('Parker', $result);
    }

    public function testGetAndSetEmail()
    {
        $user = new User();
        $user->setEmail('some@email.com');
        $result = $user->getEmail();
        $this->assertEquals('some@email.com', $result);
    }

    public function testGetAndSetPassword()
    {
        $user = new User();
        $user->setPassword('111111');
        $result = $user->getPassword();
        $this->assertEquals('111111', $result);
    }

    public function testGetAndSetPlainPassword()
    {
        $user = new User();
        $user->setPlainPassword('111111');
        $result = $user->getPlainPassword();
        $this->assertEquals('111111', $result);
    }

    public function testGetAndSetApiToken()
    {
        $user = new User();
        $user->setApiToken('de4477ce-6f1d-4186-9c3a-9b95b02d338f');
        $result = $user->getApiToken();
        $this->assertEquals('de4477ce-6f1d-4186-9c3a-9b95b02d338f', $result);
    }

    public function testGetAndSetRoles()
    {
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $result = $user->getRoles();
        $this->assertEquals(["ROLE_USER"], $result);
    }
}
