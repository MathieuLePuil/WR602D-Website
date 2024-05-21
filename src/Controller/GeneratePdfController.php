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
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createFormBuilder()
            ->add('url', null, ['required' => true])
            ->add('submit', SubmitType::class, ['label' => 'Générer PDF'])
            ->getForm();

        $form->handleRequest($request);

        $pdfUrl = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $pdfLimit = $user->getSubscription()->getPdfLimit();

            $pdfCountToday = $this->pdfRepository->countUserPdfsForToday($user);
            if ($pdfCountToday >= $pdfLimit) {
                $this->addFlash('error', 'Vous avez atteint la limite de PDFs pour aujourd\'hui');
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
        }

        return $this->render('pdf/generate_pdf.html.twig', [
            'form' => $form->createView(),
            'pdf_url' => $pdfUrl
        ]);

    }
}
