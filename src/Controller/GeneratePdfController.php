<?php

namespace App\Controller;

use App\Service\GotenbergService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GeneratePdfController extends AbstractController
{
    private $pdfService;

    public function __construct(GotenbergService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    #[Route('/generate-pdf', name: 'generate_pdf', methods: ['GET', 'POST'])]
    public function generatePdf(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createFormBuilder()
            ->add('url', null, ['required' => true])
            ->add('submit', SubmitType::class, ['label' => 'Générer PDF'])
            ->getForm();

        $form->handleRequest($request);

        $pdfUrl = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->get('url')->getData();
            $pdfUrl = $this->pdfService->generatePdfFromUrl($url);
        }

        if ($pdfUrl !== null) {
            return $this->render('pdf/generate_pdf.html.twig', [
                'form' => $form->createView(),
                'pdf_url' => $pdfUrl
            ]);
        }

        return $this->render('pdf/generate_pdf.html.twig', [
            'form' => $form->createView(),
            'pdf_url' => null
        ]);
    }
}
