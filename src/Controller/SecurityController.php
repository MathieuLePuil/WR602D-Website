<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//        if ($this->getUser()) {
//            return $this->redirectToRoute('app_home');
//        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        //nothing here
    }

    #[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $email = $form->get('email')->getData();

            $existingUser = $manager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($existingUser) {
                $this->addFlash(
                    'error',
                    'Email already exists.'
                );
                return $this->render('security/signup.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            if ($form->isValid()) {
                $user = $form->getData();

                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPlainPassword());
                $user->setPassword($hashedPassword);
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setUpdatedAt(new \DateTimeImmutable());

                $this->addFlash(
                    'success',
                    'Your account has been created. You can now log in.'
                );

                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }
}