<?php

namespace App\Controller;

use App\Form\SearchAntiPassoireType;
use App\Repository\AntiPassoireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AntiPassoireController extends AbstractController
{
    /**
     * @Route("/anti-passoire/{slug}", name="app_antipassoire_show")
     */
    public function show(
        AntiPassoireRepository $antiPassoireRepository,
        string $slug
    ): Response
    {
        $antiPassoire = $antiPassoireRepository->findOneBy(['slug' => $slug]);

        if ($antiPassoire === null) {
            throw $this->createNotFoundException("Cet anti passoire n'existe pas.");
        }

        $form = $this->createForm(SearchAntiPassoireType::class, null, [
            'action' => $this->generateUrl('app_home'),
            'method' => 'POST'
        ]);

        return $this->render('anti_passoire/index.html.twig', [
            'antiPassoire' => $antiPassoire,
            'searcherForm' => $form->createView()
        ]);
    }
}
