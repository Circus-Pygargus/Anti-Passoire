<?php

namespace App\Controller\Admin;

use App\Repository\AntiPassoireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AntiPassoireController extends AbstractController
{
    /**
     * @Route("/admin/antipassoire/list", name="admin_antipassoire_list")
     */
    public function list(AntiPassoireRepository $antiPassoireRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $antiPassoires = $antiPassoireRepository->findAll();
        
        return $this->render('admin/antipassoire/list.html.twig', [
            'antiPassoires' => $antiPassoires
        ]);
    }
}
