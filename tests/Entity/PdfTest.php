<?php
namespace App\Tests\Entity;

use App\Entity\Pdf;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $pdf = new Pdf();
        $user = new User();

        $user->setEmail('johndoe@mail.com');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setPassword('toto1234');
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $title = 'Mon pdf';
        $created_at = new \DateTimeImmutable();

        $pdf->setTitle($title);
        $pdf->setCreatedAt($created_at);
        $pdf->setUserId($user);

        $this->assertEquals($title, $pdf->getTitle());
        $this->assertEquals($created_at, $pdf->getCreatedAt());
        $this->assertEquals($user, $pdf->getUserId());
    }
}