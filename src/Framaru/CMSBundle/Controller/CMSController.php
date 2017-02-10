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

        return $this->render('CMSBundle:Default:index.html.twig', array(
            "pages" => $pages
        ));
    }
}