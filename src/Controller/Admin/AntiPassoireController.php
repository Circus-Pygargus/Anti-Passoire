<?php

namespace App\Controller\Admin;

use App\Entity\AntiPassoire;
use App\Form\AntiPassoireType;
use App\Form\ChangeAntiPassoireIsPublishedType;
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
     * @Route("/contribute/antipassoire/list", name="admin_antipassoire_list")
     */
    public function list(AntiPassoireRepository $antiPassoireRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');
        
        $antiPassoires = $antiPassoireRepository->findAll();

        $changeIsPublishedForm = $this->createForm(ChangeAntiPassoireIsPublishedType::class, null, [
            'action' => $this->generateUrl('admin_user_change_is_published'),
            'method' => 'POST',
        ]);
        
        return $this->render('admin/antipassoire/list.html.twig', [
            'antiPassoires' => $antiPassoires,
            'changeIsPublishedForm' => $changeIsPublishedForm->createView()
        ]);
    }
    
    /**
     * @Route("/contribute/antipassoire/create", name="admin_antipassoire_create")
     * @Route("/contribute/antipassoire/edit/{slug}", name="admin_antipassoire_edit")
     */
    public function edit(
        Request $request,
        AntiPassoireRepository $antiPassoireRepository,
        string $slug = null
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');
        
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
                $lastword = $isNew ? 'cr????' : '??dit??';
                $this->addFlash('success', 'L\'anti passoire <strong> ' . $antipassoire->getTitle() . '</strong> a bien ??t?? ' . $lastword . '.');
                $return = $this->redirectToRoute('admin_antipassoire_list');
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());

                $this->addFlash('error', "Un probl??me est survenu, l'anti passoire n'a pas ??t?? cr????e !");
                $return = $this->redirectToRoute('admin_antipassoire_list');
            }
        }

        return $return === null
            ? $this->render('admin/antipassoire/edit.html.twig', [
                'form' => $form->createView(),
                'pageTitlePrefix' => $isNew ? 'Cr??ation': '??dition',
            ])
            : $return
        ;
    }

    /**
     * @Route("admin/user/change-is-published", name="admin_user_change_is_published")
     */
    public function changeIsPublished (Request $request, AntiPassoireRepository $antiPassoireRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ChangeAntiPassoireIsPublishedType::class, null);
        $wantedIsPublishedForm = $form->handleRequest($request);

        if ($wantedIsPublishedForm->isSubmitted() && $wantedIsPublishedForm->isValid()) {
            try {
                $antiPassoireToEdit = $antiPassoireRepository->findOneBy(['slug' => $wantedIsPublishedForm->get('slug')->getData()]);
                if ($antiPassoireToEdit === null) {
                    $this->logger->warning('Un petit malin modifie le contenu des data-name des boutons de modifications d\'antiPassoire isPublished !');
                } else {
                    $antiPassoireToEdit->setIsPublished($wantedIsPublishedForm->get('isPublished')->getData());
                    $this->em->persist($antiPassoireToEdit);
                    $this->em->flush();
                    $successMessage =  $antiPassoireToEdit->getIsPublished()
                        ? "L'aide '" . $antiPassoireToEdit->getTitle() . " est maintenant visible sur le site."
                        : "L'aide '" . $antiPassoireToEdit->getTitle() . "' n'est plus visible sur le site.";
                    $this->addFlash('success', $successMessage);
                }
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());
                $this->addFlash('error', "Un probl??me est survenu pendant l'enregistrement");
            }
        }

        return $this->redirectToRoute('admin_antipassoire_list');
    }
}
