<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employe;
use App\Entity\Entreprise;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }

    #[Route('/entreprisesListe', name: 'app_entreprisesListe')]
    public function entreprisesList(EntityManagerInterface $entityManager): Response
    {
        $repoEmploye = $entityManager->getRepository(Employe::class);
        $repoEntreprise = $entityManager->getRepository(Entreprise::class);

        // récupération du tableau d'employés de l'entreprise
        $entreprisesListe = $repoEntreprise->findBy([], ['raisonSociale' => 'DESC']);

        return $this->render('entreprise/entreprisesList.html.twig', [
            'entreprisesArray' => $entreprisesListe
        ]);
    }


    // Détail de l'entreprise (+ Récupération du tableau d'employés de l'entreprise)
    #[Route('/entrepriseDetail/{idEntreprise}', name: 'app_entrepriseDetail')]
    public function entrepriseDetail(EntityManagerInterface $entityManager, int $idEntreprise): Response
    {
        $repoEntreprise = $entityManager->getRepository(Entreprise::class);
        $repoEmploye = $entityManager->getRepository(Employe::class);

        $entreprise = $repoEntreprise->find($idEntreprise);
        $employesList = $repoEmploye->findBy(['entreprise' => $entreprise->getId()]);

        return $this->render('entreprise/entrepriseDetail.html.twig', [
            'employesArray' => $employesList,
            'entreprise' => $entreprise
        ]);
    }
    
    


}
