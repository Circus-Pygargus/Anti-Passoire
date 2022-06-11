<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
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
     * @Route("/admin/category/list", name="admin_category_list")
     */
    public function list(CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $categories = $categoryRepository->findAll();
        
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/category/create", name="admin_category_create")
     * @Route("/admin/category/edit/{slug}", name="admin_category_edit")
     */
    public function edit(
        Request $request,
        CategoryRepository $categoryRepository,
        string $slug = null
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $return = null;

        ($isNew = $slug === null)
            ? $category = new Category()
            : $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (!$category) {
            $this->addFlash('error', 'La catégorie <strong>' . $slug . '</strong> n\'existe pas');

            $return = $this->redirectToRoute('admin_category_list');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($category);
                $this->em->flush();
                $this->addFlash('success', 'La catégorie <strong>' . $category->getLabel() . '</strong> a bien été créée.');
                $return = $this->redirectToRoute('admin_category_list');
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());

                $this->addFlash('error', 'Un problème est survenu, la catégorie n\'a pas été créée !');
                $return = $this->redirectToRoute('admin_category_list');
            }
        }

        return $return === null
            ? $this->render('admin/category/edit.html.twig', [
                'form' => $form->createView(),
                'pageTitlePrefix' => $isNew ? 'Création' : 'Édition',
            ])
            : $return;
    }
}
