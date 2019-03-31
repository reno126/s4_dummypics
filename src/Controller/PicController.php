<?php

namespace App\Controller;

use App\Entity\Pic;
use App\Form\PicType;
use App\Repository\PicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pic")
 */
class PicController extends AbstractController
{
    /**
     * @Route("/", name="pic_index", methods={"GET"})
     */
    public function index(PicRepository $picRepository): Response
    {
        return $this->render('pic/index.html.twig', [
            'pics' => $picRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pic_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pic = new Pic();
        $form = $this->createForm(PicType::class, $pic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pic);
            $entityManager->flush();

            return $this->redirectToRoute('pic_index');
        }

        return $this->render('pic/new.html.twig', [
            'pic' => $pic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pic_show", methods={"GET"})
     */
    public function show(Pic $pic): Response
    {
        return $this->render('pic/show.html.twig', [
            'pic' => $pic,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pic_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pic $pic): Response
    {
        $form = $this->createForm(PicType::class, $pic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pic_index', [
                'id' => $pic->getId(),
            ]);
        }

        return $this->render('pic/edit.html.twig', [
            'pic' => $pic,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pic $pic): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pic->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pic_index');
    }
}
