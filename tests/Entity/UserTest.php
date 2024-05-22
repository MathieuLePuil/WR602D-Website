<?php
namespace App\Tests\Entity;

use App\Entity\Subscription;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $user = new User();
        $subscription = new Subscription();

        $subscription->setTitle('Abonnement');
        $subscription->setDescription('La description de mon abonnement');
        $subscription->setPdflimit(10);
        $subscription->setPrice(9);
        $subscription->setMedia('image.jpg');

        $email = 'johndoe@mail.com';
        $firstname = 'John';
        $lastname = 'Doe';
        $password = 'toto1234';
        $roles = ['ROLE_USER'];
        $created_at = new \DateTimeImmutable();
        $updated_at = new \DateTimeImmutable();

        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setPassword($password);
        $user->setRoles($roles);
        $user->setCreatedAt($created_at);
        $user->setUpdatedAt($updated_at);

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($firstname, $user->getFirstname());
        $this->assertEquals($lastname, $user->getLastname());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($roles, $user->getRoles());
        $this->assertEquals($created_at, $user->getCreatedAt());
        $this->assertEquals($updated_at, $user->getUpdatedAt());
    }
}