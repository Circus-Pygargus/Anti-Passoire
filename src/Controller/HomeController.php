<?php

namespace App\Controller;

use App\Form\SearchAntiPassoireType;
use App\Repository\AntiPassoireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(
        Request $request,
        AntiPassoireRepository $antiPassoireRepository
    ): Response
    {
        $antiPassoires = [];
        
        $form = $this->createForm(SearchAntiPassoireType::class);
        $search = $form->handleRequest($request);
//dd($search->get('categories')->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            $antiPassoires = $antiPassoireRepository->search(
                $search->get('keyWords')->getData(),
                $search->get('category')->getData()
            );
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'antiPassoires' => $antiPassoires,
            'searcherForm' => $form->createView()
        ]);
    }
}
