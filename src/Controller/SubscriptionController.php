<?php

namespace App\Controller;

use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscriptionController extends AbstractController
{
    #[Route('/souscription', name: 'app_souscription')]
    public function index(): Response
    {
        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }

    #[Route('/souscription/{sub}', name: 'app_souscription_subscription')]
    public function subscription(EntityManagerInterface $manager, Int $sub): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $subscription = $manager->getRepository(Subscription::class)->findOneBy(['id' => $sub]);

        if (!$subscription) {
            $this->addFlash('error', 'La souscription n\'existe pas.');
            return $this->redirectToRoute('app_souscription');
        }

        $user->setSubscription($subscription);
        $user->setSubscriptionEndAt(new \DateTimeImmutable('+1 month', new \DateTimeZone('Europe/Paris')));

        $manager->persist($user);
        $manager->flush();

        if ($sub === 1) {
            $this->addFlash('success', 'Vous avez souscrit à l\'offre Basique.');
        } elseif ($sub === 2) {
            $this->addFlash('success', 'Vous avez souscrit à l\'offre Premium.');
        } elseif ($sub === 3) {
            $this->addFlash('success', 'Vous avez souscrit à l\'offre Entreprise.');
        }


        return $this->redirectToRoute('app_souscription');
    }
}
