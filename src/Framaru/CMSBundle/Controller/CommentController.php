<?php

namespace Framaru\CMSBundle\Controller;

use Framaru\CMSBundle\Entity\Comment;
use Framaru\CMSBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


/**
 * Comment controller.
 *
 * @Route("cms/comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all comment entities.
     *
     * @Route("/", name="cms_comment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('CMSBundle:Comment')->findAll();

        return $this->render('comment/index.html.twig', array(
            'comments' => $comments,
        ));
    }

    /**
     * Creates a new comment entity.
     *
     * @Route("/new", name="cms_comment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $page_id = $request->get("page_id");

        $page = $this->getDoctrine()->getRepository("CMSBundle:Page")->find($page_id);
        $comment = new Comment();

        $comment->setPage($page);

        $form = $this->createForm('Framaru\CMSBundle\Form\CommentType', $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('cms_page_display', array('id' => $page_id));
        }

        return $this->render('comment/new.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a comment entity.
     *
     * @Route("/{id}", name="cms_comment_show")
     * @Method("GET")
     */
    public function showAction(Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);

        return $this->render('comment/show.html.twig', array(
            'comment' => $comment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing comment entity.
     *
     * @Route("/{id}/edit", name="cms_comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comment $comment)
    {
        $deleteForm = $this->createDeleteForm($comment);
        $editForm = $this->createForm('Framaru\CMSBundle\Form\CommentType', $comment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cms_comment_edit', array('id' => $comment->getId()));
        }

        return $this->render('comment/edit.html.twig', array(
            'comment' => $comment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Display a form to flag a comment
     *
     * @Route("/{id}/flag", name="cms_comment_flag")
     * @Method({"GET", "POST"})
     */
    public function flagAction(Request $request, Comment $comment)
    {
        $flagForm = $this->createFormBuilder($comment)
            ->add("flag", RadioType::class, array(
                "label" => "Oui :"
            ))
            ->add('save', SubmitType::class, array('label' => 'Signaler'))
            ->getForm();

        $flagForm->handleRequest($request);

        if ($flagForm->isSubmitted() && $flagForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('cms_page_display', array('id' => $comment->getPage()->getId()));
        }

        return $this->render(":comment:flagComment.html.twig", array(
            "comment" => $comment,
            "flag_form" => $flagForm->createView(),
            'id' => $comment->getPage()->getId()
        ));
    }

    /**
     * Deletes a comment entity.
     *
     * @Route("/{id}", name="cms_comment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        $form = $this->createDeleteForm($comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush($comment);
        }

        return $this->redirectToRoute('cms_comment_index');
    }

    /**
     * Creates a form to delete a comment entity.
     *
     * @param Comment $comment The comment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cms_comment_delete', array('id' => $comment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a response to a comment.
     *
     * @Route("/respond/{id}", name="cms_comment_respond")
     * @Method({"GET", "POST"})
     *
     */
    public function respondAction(Request $request, Comment $parent)
    {
        $comment = new Comment();

        $comment->setParent($parent);


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('cms_page_display', array('id' => $comment->getPage()->getId()));
        }

        return $this->render('comment/respond.html.twig', array(
            'comment' => $comment,
            "parent" => $parent,
            'form' => $form->createView(),
        ));
    }
}
