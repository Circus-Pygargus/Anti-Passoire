<?php

namespace App\Controller\Admin;

use App\Entity\AntiPassoire;
use App\Form\AntiPassoireType;
use App\Repository\AntiPassoireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AntiPassoireController extends AbstractController
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger
    )
    {
        $this->em = $em;
        $this->logger = $logger;
    }
    
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
    
    /**
     * @Route("/admin/antipassoire/create", name="admin_antipassoire_create")
     * @Route("/admin/antipassoire/edit/{slug}", name="admin_antipassoire_edit")
     */
    public function edit(
        Request $request,
        AntiPassoireRepository $antiPassoireRepository,
        string $slug = null
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $return = null;

        ($isNew = $slug === null)
            ? $antipassoire = new AntiPassoire()
            : $antipassoire = $antiPassoireRepository->findOneBy(['slug'=> $slug]);
        
        if (!$antipassoire) {
            $this->addFlash('error', `L'anti passoire <strong>{$slug}</strong> n'existe pas.`);
            $return = $this->redirectToRoute('admin_antipassoire_list');
        }
        
        $form = $this->createForm(AntiPassoireType::class, $antipassoire);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $antipassoire->setCreator($this->getUser());
                $antipassoire->addContributor($this->getUser());
                $this->em->persist($antipassoire);
                $this->em->flush();
                $lastword = $isNew ? 'créé' : 'édité';
                $this->addFlash('success', 'L\'anti passoire <strong> ' . $antipassoire->getTitle() . '</strong> a bien été ' . $lastword . '.');
                $return = $this->redirectToRoute('admin_antipassoire_list');
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());

                $this->addFlash('error', "Un problème est survenu, l'anti passoire n'a pas été créée !");
                $return = $this->redirectToRoute('admin_antipassoire_list');
            }
        }

        return $return === null
            ? $this->render('admin/antipassoire/edit.html.twig', [
                'form' => $form->createView(),
                'pageTitlePrefix' => $isNew ? 'Création': 'Édition',
            ])
            : $return
        ;
    }
}
