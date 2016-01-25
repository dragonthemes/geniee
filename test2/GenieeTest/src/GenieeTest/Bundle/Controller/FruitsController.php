<?php

namespace GenieeTest\Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GenieeTest\Bundle\Entity\Fruits;
use GenieeTest\Bundle\Form\FruitsType;

/**
 * Fruits controller.
 *
 * @Route("/fruits")
 */
class FruitsController extends Controller
{
    /**
     * Lists all Fruits entities.
     *
     * @Route("/", name="fruits_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $fruits = $em->getRepository('GenieeTestBundle:Fruits')->findAll();

        $form = $this->createAddnewForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {            
            $fruit = new Fruits();

            $fruit->setName($request->query->get('name'));
            $fruit->setOrigin($request->query->get('origin'));

            $em->persist($fruit);
            $em->flush();

            return $this->redirectToRoute('fruits_index');
        }

        return $this->render('fruits/index.html.twig', array(
            'fruits' => $fruits,
            'addnew_form' => $form->createView()
        ));
    }

    /**
     * Creates a new Fruits entity.
     *
     * @Route("/new", name="fruits_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fruit = new Fruits();
        $form = $this->createForm('GenieeTest\Bundle\Form\FruitsType', $fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fruit);
            $em->flush();

            return $this->redirectToRoute('fruits_show', array('id' => $fruits->getId()));
        }

        return $this->render('fruits/new.html.twig', array(
            'fruit' => $fruit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Fruits entity.
     *
     * @Route("/{id}", name="fruits_show")
     * @Method("GET")
     */
    public function showAction(Fruits $fruit)
    {
        $deleteForm = $this->createDeleteForm($fruit);

        return $this->render('fruits/show.html.twig', array(
            'fruit' => $fruit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Fruits entity.
     *
     * @Route("/{id}/edit", name="fruits_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Fruits $fruit)
    {
        $deleteForm = $this->createDeleteForm($fruit);
        $editForm = $this->createForm('GenieeTest\Bundle\Form\FruitsType', $fruit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $fruit->setName($request->request->get('name'));
            $fruit->setOrigin($request->request->get('origin'));

            $em->persist($fruit);
            $em->flush();

            return $this->redirectToRoute('fruits_edit', array('id' => $fruit->getId()));
        }

        return $this->render('fruits/edit.html.twig', array(
            'fruit' => $fruit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Fruits entity.
     *
     * @Route("/{id}", name="fruits_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Fruits $fruit)
    {
        $form = $this->createDeleteForm($fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fruit);
            $em->flush();
        }

        return $this->redirectToRoute('fruits_index');
    }

    /**
     * Creates a form to delete a Fruits entity.
     *
     * @param Fruits $fruit The Fruits entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Fruits $fruit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fruits_delete', array('id' => $fruit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Creates a form to add new a Fruits entity.
     *
     * @param Fruits $fruit The Fruits entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createAddnewForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fruits_index', array()))
            ->setMethod('GET')
            ->getForm()
        ;
    }
}
