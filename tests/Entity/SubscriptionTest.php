<?php
// tests/Entity/UserTest.php
namespace App\Tests\Entity;

use App\Entity\Subscription;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $subscription = new Subscription();

        $title = 'Abonnement';
        $description = 'La description de mon abonnement';
        $pdflimit = 10;
        $price = 9;
        $media = 'image.jpg';

        $subscription->setTitle($title);
        $subscription->setDescription($description);
        $subscription->setPdflimit($pdflimit);
        $subscription->setPrice($price);
        $subscription->setMedia($media);

        $this->assertEquals($title, $subscription->getTitle());
        $this->assertEquals($description, $subscription->getDescription());
        $this->assertEquals($pdflimit, $subscription->getPdflimit());
        $this->assertEquals($price, $subscription->getPrice());
        $this->assertEquals($media, $subscription->getMedia());
    }
}