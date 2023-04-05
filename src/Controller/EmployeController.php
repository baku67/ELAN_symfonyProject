<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employe;
use App\Entity\Entreprise;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
        ]);
    }

    // Récupération du tableau de tout les employés 
    #[Route('/employesListe', name: 'app_employeList')]
    public function employeList(EntityManagerInterface $entityManager): Response
    {
        $repoEmploye = $entityManager->getRepository(Employe::class);
        $repoEntreprise = $entityManager->getRepository(Entreprise::class);

        // récupération du tableau d'employés de l'entreprise
        $employesList = $repoEmploye->findAll();

        return $this->render('employe/globalEmployesList.html.twig', [
            'employesArray' => $employesList
        ]);
    }


    // Récupération du tableau d'employés de l'entreprise (Déplacé dans /entrepriseDetail)
    // #[Route('/entrepriseEmployesListe/{idEntreprise}', name: 'app_entrepriseEmployesListe')]
    // public function entrepriseEmployesListe(EntityManagerInterface $entityManager, int $idEntreprise): Response
    // {
    //     $repoEmploye = $entityManager->getRepository(Employe::class);
    //     $repoEntreprise = $entityManager->getRepository(Entreprise::class);

    //     $entreprise = $repoEntreprise->find($idEntreprise);
    //     $employesList = $repoEmploye->findBy(['entreprise' => $entreprise->getId()]);

    //     return $this->render('employe/employesList.html.twig', [
    //         'employesArray' => $employesList,
    //         'entreprise' => $entreprise
    //         // 'entrepriseName' => $repoEntreprise->find($entreprise)->getRaisonSociale(),
    //         // OU 'entreprise' => $repoEntreprise->find($idEntreprise) (et récup le .nom dans la vue),
    //     ]);
    // }


    // Détail de l'employé
    #[Route('/employeDetail/{idEmploye}', name: 'app_employeDetail')]
    public function employeDetail(EntityManagerInterface $entityManager, int $idEmploye): Response
    {
        $repoEmploye = $entityManager->getRepository(Employe::class);

        $employe = $repoEmploye->find($idEmploye);

        return $this->render('employe/employeDetail.html.twig', [
            'employe' => $employe
        ]);
    }


}
