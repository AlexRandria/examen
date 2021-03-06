<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Form\DepartementFormType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin",name="admin_")
 */
class DepartementController extends AbstractController
{
    /**
     * Liste des départements du site
     * @Route("/departement", name="departement")
     */
    public function index(DepartementRepository $departementRepository): Response
    {
        $listeDepartement = $departementRepository->findAll();
        return $this->render('departement/index.html.twig', [
            'listeDepartement' => $listeDepartement,
        ]);
    }


    /**
     * @Route("/departement/ville/{id}", name="departementVille")
     * liste des villes d'un Departement donné
     */
    public function villeByDepartement(VilleRepository $villeRepository, $id): Response
    {

        $departement = $this->getDoctrine()->getRepository(Departement::class)->find($id);

        $listeVille = $villeRepository->findBy(['Departement' => $departement]);

        return $this->render('departement/ville.html.twig', [
            'listeVille' => $listeVille,
        ]);
    }

    /**
     * @Route("/departement/add",name="ajoutDepartement")
     */
    public function addDepartement(Request $request, EntityManagerInterface $em)
    {

        $departement = new Departement;
        $form = $this->createForm(DepartementFormType::class, $departement);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($departement);
            $em->flush();

            $this->addFlash('success', 'Département ajouté avec succès');

            return $this->redirectToRoute('admin_departement');
        }


        return $this->render('departement/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/departement/edit/{id}",name="editDepartement")
     */
    public function editDepartement(Request $request, EntityManagerInterface $em, $id)
    {

        $departement = $em->getRepository(Departement::class)->find($id);
        $form = $this->createForm(DepartementFormType::class, $departement);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($departement);
            $em->flush();

            return $this->redirectToRoute('success');
        }


        return $this->render('departement/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/departement/remove/{id}",name="removeDepartement")
     */
    public function removeDepartement(Departement $departement, EntityManagerInterface $em, $id)
    {
        $em->remove($departement);
        $em->flush();

        $this->addFlash('success', 'Département supprimé avec succès');
        
        return $this->redirectToRoute('admin_departement');
    }
}
