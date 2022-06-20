<?php

namespace App\Controller;

use App\Form\SearchAntiPassoireType;
use App\Repository\AntiPassoireRepository;
use App\Service\Pagination\PaginationService;
use App\Service\Validator\SearcherOrderValidator;
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
        $searcherResponseMsg = '';

        $form = $this->createForm(SearchAntiPassoireType::class);
        $search = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && SearcherOrderValidator::isOrderValid($search)) {
            $searchLimit = $search->get('searchLimit')->getData();
            $page = $search->get('pageNumber')->getData();
            $antiPassoires = $antiPassoireRepository->search(
                $search->get('keyWords')->getData(),
                $search->get('category')->getData(),
                $search->get('orderBy')->getData(),
                $search->get('orderDirection')->getData(),
                $page,
                $searchLimit
            );
            $totalFound = $antiPassoireRepository->getTotalForResarch(
                $search->get('keyWords')->getData(),
                $search->get('category')->getData()
            );
            $antiPassoiresGiven = count($antiPassoires);
            if ($antiPassoiresGiven) {
                if ($antiPassoiresGiven !== $totalFound) {
                    $searcherResponseMsg = 'Voici ' . $antiPassoiresGiven . ' des ' . $totalFound . ' résultats possibles pour cette recherche :';
                } else {
                    $searcherResponseMsg = $antiPassoiresGiven . ' résultat' . ($antiPassoiresGiven > 1 ? 's' : '') . ' pour cette recherche :';
                }
            } else {
                $searcherResponseMsg = 'Cette recherche ne donne aucun résultat !';
            }
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'searcherForm' => $form->createView(),
            'searcherResponseMsg' => $searcherResponseMsg,
            'antiPassoires' => $antiPassoires,
            'totalFound' => $totalFound,
            'searchLimit' => $searchLimit,
            'actualPageValue' => $page,
            'paginationHelper' => $paginationService->getBuilderHelpers(ceil($totalFound / $searchLimit), $page)
        ]);
    }
}
