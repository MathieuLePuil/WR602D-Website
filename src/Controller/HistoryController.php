<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PdfRepository;

class HistoryController extends AbstractController
{
    #[Route('/historique', name: 'app_history')]
    public function index(PdfRepository $pdfRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $pdfs = $pdfRepository->findByUserOrderedByDate($user);

        return $this->render('history/index.html.twig', [
            'pdfs' => $pdfs,
        ]);
    }
}
