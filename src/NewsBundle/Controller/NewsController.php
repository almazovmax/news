<?php
namespace NewsBundle\Controller;

use NewsBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\Session;

class NewsController extends Controller
{
    private $limitPerPage=10;

    /**
     * Lists all news entities for index page.
     *
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $pagination = $this->pagination($request);

        return $this->render('NewsBundle:Page:index.html.twig', array(
            'news' => $pagination,
        ));
    }

    /**
     * Lists all news entities for Admin.
     *
     * @Route("news/", name="news_index")
     * @Method("GET")
     */
    public function listNewsAction(Request $request)
    {
        $pagination = $this->pagination($request);
        $message = $this->isFlash();

        return $this->render('NewsBundle:Page:grid.html.twig', array(
            'news' => $pagination,
            'message' =>$message,
        ));
    }

    /**
     * Creates a new news entity.
     *
     * @Route("news/new", name="news_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $news = new News();
        $form = $this->createForm('NewsBundle\Form\NewsType', $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileName = $this->uploadImage($news);
            $news->setImage('images/'.$fileName);
            $news->setDate(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush($news);

            $this->setFlash('News add successfully');

            return $this->redirectToRoute('news_index');
        }

        return $this->render('NewsBundle:Page:new.html.twig', array(
            'news' => $news,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a news entity.
     *
     * @Route("show/{id}", name="news_show")
     * @Method("GET")
     */
    public function showAction(News $news)
    {
        return $this->render('NewsBundle:Page:news.html.twig', array(
            'news' => $news,
        ));
    }

    /**
     * Displays a form to edit an existing news entity.
     *
     * @Route("news/{id}/edit", name="news_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, News $news)
    {
        $editForm = $this->createForm('NewsBundle\Form\NewsType', $news);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($news->getImage()) {
                $fileName = $this->uploadImage($news);
                $news->setImage('images/'.$fileName);

                $this->getDoctrine()->getManager()->flush();
            } else {
                $this->getDoctrine()->getRepository('NewsBundle:News')->updateEntity($news);
            }

            $this->setFlash('News edit successfully');

            return $this->redirectToRoute('news_index', array('id' => $news->getId()));
        }

        return $this->render('NewsBundle:Page:edit.html.twig', array(
            'news' => $news,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a news entity.
     *
     * @Route("news/{id}", name="news_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, News $news)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush($news);

        return $this->redirectToRoute('news_index');
    }

    private function pagination(Request $request, $entity = 'News')
    {
        $query = $this->get('pagination')->paginator($request, $entity);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $request->query->getInt('page', 1), $this->limitPerPage);

        return $pagination;
    }

    private function isFlash()
    {
        $session = new Session();
        if($session->getFlashBag()->has('Reset')){
            foreach ($session->getFlashBag()->get('Reset', array()) as $message) {
            }
        } else {
            $message = null;
        }

        return $message;
    }

    private function setFlash($message)
    {
        $session = new Session();

        return $session
            ->getFlashBag()
            ->add('Reset', $message);
    }

    private function uploadImage($news)
    {
        $file = $news->getImage();
        $fileName = $this->get('app.images_uploader')->upload($file);

        return $fileName;
    }
}
