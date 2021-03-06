<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $listeVilles = $em->getRepository(Ville::class)->findAll();
        return $this->render('index.html.twig', ['listeVilles' => $listeVilles]);
    }
}
