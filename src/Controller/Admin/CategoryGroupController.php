<?php

namespace App\Controller\Admin;

use App\Entity\CategoryGroup;
use App\Form\CategoryGroupType;
use App\Repository\CategoryGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryGroupController extends AbstractController
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
     * @Route("/contribute/category-group/list", name="admin_category_group_list")
     */
    public function list(CategoryGroupRepository $categoryGroupRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $categoryGroups = $categoryGroupRepository->findAllForUser();

        return $this->render('admin/category_group/list.html.twig', [
            'categoryGroups' => $categoryGroups
        ]);
    }

    /**
     * @Route("/contribute/category-group/create", name="admin_category_group_create")
     * @Route("/contribute/category-group/edit/{slug}", name="admin_category_group_edit")
     */
    public function edit(
        Request $request,
        CategoryGroupRepository $categoryGroupRepository,
        string $slug = null
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CONTRIBUTOR');

        $return = null;

        ($isNew = $slug === null)
            ? $categoryGroup = new CategoryGroup()
            : $categoryGroup = $categoryGroupRepository->findOneBy(['slug' => $slug]);

        if (!$categoryGroup) {
            $this->addFlash('error', 'Le groupe de catégories <strong>' . $slug . '</strong> n\'existe pas');

            $return = $this->redirectToRoute('admin_category_group_list');
        }

        $form = $this->createForm(CategoryGroupType::class, $categoryGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($categoryGroup);
                $this->em->flush();
                $this->addFlash('success', 'Le groupe de catégories <strong>' . $categoryGroup->getLabel() . '</strong> a bien été créé.');
                $return = $this->redirectToRoute('admin_category_group_list');
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());

                $this->addFlash('error', 'Un problème est survenu, le groupe de catégories n\'a pas été créé !');
                $return = $this->redirectToRoute('admin_category_group_list');
            }
        }

        return $return === null
            ? $this->render('admin/category_group/edit.html.twig', [
                'form' => $form->createView(),
                'pageTitlePrefix' => $isNew ? 'Création' : 'Édition',
            ])
            : $return;
    }
}
