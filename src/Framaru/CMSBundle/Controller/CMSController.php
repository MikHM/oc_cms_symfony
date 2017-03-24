<?php

namespace Framaru\CMSBundle\Controller;

use Framaru\CMSBundle\Entity\Comment;
use Framaru\CMSBundle\Entity\Page;
use Framaru\CMSBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CMSController extends Controller
{
    /**
     * @Route("/", name="cms_homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository("CMSBundle:Page")->findAll();

        return $this->render('CMSBundle:CMS:index.html.twig', array(
            "pages" => $pages
        ));
    }


    /**
     * @Route("/page/{id}", name="cms_page_display")
     */
    public function pageDisplayAction(Request $request, Page $page)
    {
        $em = $this->getDoctrine()->getManager();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $comment->setPage($page);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('cms_page_display', array('id' => $id));
        }


        return $this->render("CMSBundle:CMS:pageDisplay.html.twig", array(
            "allComments" => $page->getComments(),
            "form" => $form->createView(),
            "page" => $page
        ));
    }
}