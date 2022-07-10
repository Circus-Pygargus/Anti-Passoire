<?php

namespace App\Controller\Admin;

use App\Entity\CategoryGroup;
use App\Form\ChangeUserRoleType;
use App\Repository\CategoryGroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
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
     * @Route("/admin/user/list", name="admin_user_list")
     */
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $userRepository->findAll();
        $form = $this->createForm(ChangeUserRoleType::class, null, [
            'action' => $this->generateUrl('admin_user_role_edit'),
            'method' => 'POST',
        ]);

        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
            'roleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/user/role/edit", name="admin_user_role_edit")
     */
    public function editUserRole (
        Request $request,
        UserRepository $userRepository,
        CategoryGroupRepository $categoryGroupRepository
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ChangeUserRoleType::class, null);
        $wantedRoleForm = $form->handleRequest($request);
        if ($wantedRoleForm->isSubmitted() && $wantedRoleForm->isValid()) {
            try {
                $userToEdit = $userRepository->findOneBy(['username' => $wantedRoleForm->get('name')->getData()]);
                if ($userToEdit === null) {
                    $this->logger->warning('Un petit malin modifie le contenu des data-name des boutons de modifications de rôle utilisateur !');
                } else {
                    $wantedRole = $wantedRoleForm->get('wantedBiggestRole')->getData();
                    $wantedRole !== 'USER'
                        ? $userToEdit->setRoles(['ROLE_' . $wantedRole])
                        : $userToEdit->setRoles([])
                    ;
                    $this->em->persist($userToEdit);

                    if ($wantedRole === 'CONTRIBUTOR') {
                        if (!$categoryGroupRepository->findOneBy(['label' => '_Privé_%', 'creator' => $userToEdit])) {
                            $categoryGroup = new CategoryGroup();
                            $categoryGroup
                                ->setLabel(\uniqid('_Privé_', true) . \random_int(0, 10^9))
                                ->setCreator($userToEdit);
                            $this->em->persist($categoryGroup);
                        }
                    }

                    $this->em->flush();
                    $this->addFlash('success', $userToEdit->getUserIdentifier() . " à maintenant le ROLE_$wantedRole");
                }
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                $this->logger->debug($exception->getTraceAsString());
                $this->addFlash('error', "Un problème est survenu pendant l'enregistrement");
            }
        }

        return $this->redirectToRoute('admin_user_list');
    }
}
