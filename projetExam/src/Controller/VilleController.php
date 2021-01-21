<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Form\VilleFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/ville", name="admin_")
 */
class VilleController extends AbstractController
{
    
     /**
     * @Route("/", name="ville_index")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $listeVilles = $em->getRepository(Ville::class)->findAll();
        return $this->render('index.html.twig', ['listeVilles' => $listeVilles]);
    }
    
    /**
     * @Route("/new", name="ville_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $path = $this->getParameter('app.dir.public') . '/img';

        $ville = new Ville();
        $form = $this->createForm(VilleFormType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $ville->setSlug($slugger->slug($ville->getName()));

            $file = $form['image']->getData();

            if ($file) {
                // récup nom de fichier sans extension
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();
                // set nom dans la propriété Img
                $ville->setImage($newFilename);

                //Déplacer le fichier dans le répertoire public + sous répertoire
                try {
                    $file->move($path, $newFilename);
                } catch (FileException $e) {
                    echo $e->getMessage();
                }
            }
            $em->persist($ville);
            $em->flush();
            $this->addFlash('success','La ville a bien été créer');
            return $this->redirectToRoute('home');
        }

        return $this->render('ville/new.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ville_show", methods={"GET"})
     */
    public function show(Ville $ville): Response
    {
        return $this->render('ville/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="ville_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ville $ville): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success','La ville a bien été modifier');
            return $this->redirectToRoute('admin_ville_show', ['id'=>$ville->getId()]);
        }

        return $this->render('ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ville_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ville $ville): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ville);
            $entityManager->flush();
        }
        $this->addFlash('success','La ville a bien été supprimer');
        return $this->redirectToRoute('home');
    }
}
