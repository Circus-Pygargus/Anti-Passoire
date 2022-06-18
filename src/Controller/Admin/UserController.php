<?php

namespace App\Controller\Admin;

use App\Form\ChangeUserRoleType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/list", name="admin_user_list")
     */
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $userRepository->findAll();
        $form = $this->createForm(ChangeUserRoleType::class);

        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
            'roleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/user/role/edit", name="admin_user_role_edit")
     */
    public function editUserRole (Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ChangeUserRoleType::class);
        $wantedRoleForm = $form->handleRequest($request);
        dd($wantedRoleForm);

        return $this->redirectToRoute('admin_user_list');
    }
}
