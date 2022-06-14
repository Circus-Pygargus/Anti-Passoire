<?php

namespace App\Controller;

use App\Form\SearchAntiPassoireType;
use App\Repository\AntiPassoireRepository;
use App\Service\Pagination\PaginationService;
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
        AntiPassoireRepository $antiPassoireRepository,
        PaginationService $paginationService
    ): Response
    {
        $antiPassoires = [];
        $totalFound = 0;
        $searchLimit = 10;
        $page = 1;
//dd($page);
        $form = $this->createForm(SearchAntiPassoireType::class);
        $search = $form->handleRequest($request);
//dd($search->get('categories')->getData());
//dd($search->get('limit')->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            $searchLimit = $search->get('searchLimit')->getData();
            $page = $page = $search->get('pageNumber')->getData();
            $antiPassoires = $antiPassoireRepository->search(
                $search->get('keyWords')->getData(),
                $search->get('category')->getData(),
                $page,
                $searchLimit
            );
            $totalFound = $antiPassoireRepository->getTotalForResarch(
                $search->get('keyWords')->getData(),
                $search->get('category')->getData()
            );
//            dd($paginationService->getBuilderHelpers($totalFound, $page));
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'searcherForm' => $form->createView(),
            'antiPassoires' => $antiPassoires,
            'totalFound' => $totalFound,
            'searchLimit' => $searchLimit,
            'actualPageValue' => $page,
            'paginationHelper' => $paginationService->getBuilderHelpers(ceil($totalFound / $searchLimit), $page)
        ]);
    }
}
