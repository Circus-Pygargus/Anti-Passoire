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
            $this->addFlash('error', "Un problème est survenu, l'anti passoire n'a pas été créée !");
            $return = $this->redirectToRoute('admin_antipassoire_list');
        } else {
            $return = $this->render('anti_passoire/index.html.twig', [
                'antiPassoire' => $antiPassoire,
                'searcherForm' => $this->createForm(SearchAntiPassoireType::class)->createView()
            ]);
        }
        
        return $return;
    }
}
