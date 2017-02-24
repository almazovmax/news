<?php
namespace NewsBundle\Controller;

use NewsBundle\Entity\User;
use NewsBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    private $limitPerPage = 10;

    /**
     * Lists all news entities for index page.
     *
     * @Route("/user", name="user_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->pagination($request);

        return $this->render('NewsBundle:Page:indexUser.html.twig', array(
            'users' => $pagination,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/register", name="register")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRole('ROLE_USER');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->setFlash('Registration completed successfully. You can log in using your username and password.');

            return $this->redirectToRoute('login');
        }

        return $this->render('NewsBundle:Page:register.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/user/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm('NewsBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getRepository('NewsBundle:User')->updateRole($user);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('NewsBundle:Page:editUser.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }

    private function pagination(Request $request, $entity = 'User')
    {
        $query = $this->get('pagination')->paginator($request, $entity);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $this->limitPerPage);

        return $pagination;
    }

    private function setFlash($message)
    {
        $session = new Session();

        return $session
            ->getFlashBag()
            ->add('Reset', $message);
    }
}
