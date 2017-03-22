<?php

namespace Framaru\CMSBundle\Controller;

use Framaru\CMSBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function pageDisplayAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository("CMSBundle:Page")->find($id);

        $comment = new Comment();
        $comment->setAuthor("Mik");
        $comment->setContent("Trolololo");
        $comment->setCreatedAt(new \DateTime);
        $comment->setPage($page);

        $allComments = $em->getRepository("CMSBundle:Comment")->findAll();

        $em->persist($comment);
        $em->flush();

        return $this->render("CMSBundle:CMS:pageDisplay.html.twig", array(
            "info" => "information",
            "allComments" => $allComments,
            "page" => $page
        ));
    }
}