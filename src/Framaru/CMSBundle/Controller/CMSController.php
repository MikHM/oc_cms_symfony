<?php

namespace Framaru\CMSBundle\Controller;

use Framaru\CMSBundle\Entity\Comment;
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
    public function pageDisplayAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository("CMSBundle:Page")->find($id);

        $comment = new Comment();

        $form = $this->createForm("Framaru\CMSBundle\Form\CommentType", $comment);
        $form->handleRequest($request);

        /*$comment->setAuthor("Mik");
        $comment->setContent("Trolololo");
        $comment->setCreatedAt(new \DateTime);*/
        $comment->setPage($page);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('cms_page_display', array('id' => $id));
        }

        /* Selecting the appropriate comments */
        $allComments = $em->getRepository("CMSBundle:Comment")->findBy(array("page" => $id),array('createdAt' => 'desc'), 5, 0);

        return $this->render("CMSBundle:CMS:pageDisplay.html.twig", array(
            "allComments" => $allComments,
            "form" => $form->createView(),
            "page" => $page
        ));
    }
}