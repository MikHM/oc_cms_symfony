<?php

namespace Framaru\CMSBundle\Controller;

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

        return $this->render("CMSBundle:CMS:pageDisplay.html.twig", array(
            "page" => $page
        ));
    }
}