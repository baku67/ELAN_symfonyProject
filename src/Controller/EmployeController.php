<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employe;
use App\Entity\Entreprise;
use App\Form\EmployeType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
        ]);
    }

    // Récupération du tableau de tout les employés 
    #[Route('/employesListe', name: 'app_employesListe')]
    public function employeList(EntityManagerInterface $entityManager): Response
    {
        $repoEmploye = $entityManager->getRepository(Employe::class);
        $repoEntreprise = $entityManager->getRepository(Entreprise::class);

        // récupération du tableau d'employés de l'entreprise
        $employesList = $repoEmploye->findBy([], ["nom" => "ASC"]);

        return $this->render('employe/globalEmployesList.html.twig', [
            'employesArray' => $employesList
        ]);
    }



    // Gère l'affichage du form d'ajout/modification MAIS GERE AUSSI l'envoi du form (if isSubmitted ou juste affichage form)
    #[Route('/employe/{id}/edit', name: 'app_editEmploye')]
    #[Route('/employe/add', name: 'app_addEmploye')]
    public function add(EntityManagerInterface $entityManager, Employe $employe = null, Request $request): Response  {

        // On vérifie dans quel cas on est (création ou modification de l'entité)
        if(!$employe) {
            $employe = new Employe();
        }

        $form = $this->createForm(EmployeType::class, $employe);
        $form -> handleRequest($request);

        // Vérifs/Filtres
        if($form->isSubmitted()) {
            if($form->isValid()) {

                // Hydrataion "Employe $employe" a partir des données du form
                $employe = $form->getData();
                // Equivalent au prepare et execute PDO (persist() avant si ajout en BDD !)
                $entityManager->persist($employe);
                $entityManager->flush();

                return $this->redirectToRoute('app_employesListe');
            }
        }

        // View qui affiche le formuaire d'ajout
        return $this->render('employe/add.html.twig', [
            'formAddEmploye' => $form->createView(),
            // Si y'a Id c'est q'on modifie (sinon renvoie false, = création), pour le titre de la page
            "edit" => $employe->getId()
        ]);
    }



    #[Route('/employe/{id}/delete', name: 'app_deleteEmploye')]
    public function delete(EntityManagerInterface $entityManager, Employe $employe): Response {
    
        // Suppression
        $entityManager->remove($employe);
        // pas de persist() ici (uniquement pour ajout en BDD), execution de l'action avec flush
        $entityManager->flush();

        // Redirection sur la route d'affichage de la liste 
        return $this->redirectToRoute('app_employesListe');

    }



    // Détail de l'employé (méthode longue/compliquée)
    // #[Route('/employeDetail/{id}', name: 'app_employeDetail')]
    // public function employeDetail(EntityManagerInterface $entityManager, int $id): Response
    // {
    //     $repoEmploye = $entityManager->getRepository(Employe::class);

    //     $employe = $repoEmploye->find($id);

    //     return $this->render('employe/employeDetail.html.twig', [
    //         'employe' => $employe
    //     ]);
    // }



    // Détail de l'employé (méthode rapide/opti)
    #[Route('/employeDetail/{id}', name: 'app_employeDetail')]
    public function employeDetail(Employe $employe): Response
    {
        return $this->render('employe/employeDetail.html.twig', [
            'employe' => $employe
        ]);
    }


}
