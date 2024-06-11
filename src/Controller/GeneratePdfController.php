<?php

namespace App\Controller;

use App\Entity\Pdf;
use App\Service\GotenbergService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\PdfRepository;
use Symfony\Component\Validator\Constraints\Url;


class GeneratePdfController extends AbstractController
{
    private $pdfService;
    private $pdfRepository;

    public function __construct(GotenbergService $pdfService,  PdfRepository $pdfRepository)
    {
        $this->pdfService = $pdfService;
        $this->pdfRepository = $pdfRepository;
    }

    #[Route('/generate-pdf', name: 'generate_pdf', methods: ['GET', 'POST'])]
    public function generatePdf(Request $request, EntityManagerInterface $manager, PdfRepository $pdfRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if (!$this->getUser()->getSubscription()) {
            $this->addFlash('error', 'Vous devez souscrire à un abonnement pour accéder à cette fonctionnalité');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createFormBuilder()
            ->add('url', null, [
                'required' => true,
                'label' => 'URL de la page',
                'attr' => [
                    'placeholder' => 'URL de la page',
                    'class' => 'px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600 mt-[5px]'
                ],
                'constraints' => [
                    new Url([
                        'message' => 'Veuillez entrer une URL valide.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Générer PDF',
                'attr' => [
                    'class' => 'bg-blue-500 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none w-[200px]'
                ]
            ])
            ->getForm();

        $errors = $form->getErrors(true);

        $form->handleRequest($request);

        $pdfUrl = null;
        $user = $this->getUser();
        $pdfLimit = $user->getSubscription()->getPdfLimit();
        $pdfCountToday = $this->pdfRepository->countUserPdfsForToday($user);
        $count = $pdfLimit - $pdfCountToday;

        if ($form->isSubmitted() && $form->isValid()) {

            if ($pdfCountToday >= $pdfLimit) {
                $this->addFlash('error', 'Vous avez atteint la limite de PDF pour aujourd\'hui');
                return $this->redirectToRoute('generate_pdf');
            }

            $url = $form->get('url')->getData();
            $pdfUrl = $this->pdfService->generatePdfFromUrl($url);

            $url = $form->get('url')->getData();
            $host = parse_url($url, PHP_URL_HOST);

            $pdf = new Pdf();
            $pdf->setTitle($host);
            $pdf->setLink($pdfUrl);
            $pdf->setUser($user);
            $pdf->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($pdf);
            $manager->flush();

            $this->addFlash('success', 'PDF généré avec succès !');
        }

        return $this->render('pdf/generate_pdf.html.twig', [
            'form' => $form->createView(),
            'pdf_url' => $pdfUrl,
            'count' => $count,
            'errors' => $errors,
        ]);

    }
}
