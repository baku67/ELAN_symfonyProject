<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employe;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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



    // Gère l'affichage du form d'ajout/modification MAIS GERE AUSSI l'envoi du form (if isSubmitted  ou juste affichage form )
    #[Route('/entreprise/{id}/edit', name: 'app_editEntreprise')]
    #[Route('/entreprise/add', name: 'app_addEntreprise')]
    public function add(EntityManagerInterface $entityManager, Entreprise $entreprise = null, Request $request): Response  {

        // On vérifie dans quel cas on est (création ou modification de l'entité)
        if(!$entreprise) {
            $entreprise = new Entreprise();
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form -> handleRequest($request);

        // Vérifs/Filtres
        if($form->isSubmitted()) {
            if($form->isValid()) {

                // Hydrataion "Entreprise $entreprise" a partir des données du form
                $entreprise = $form->getData();
                // Equivalent au prepare et execute PDO (persist() avant si ajout en BDD !)
                $entityManager->persist($entreprise);
                $entityManager->flush();

                return $this->redirectToRoute('app_entreprisesListe');
            }
        }


        // View qui affiche le formuaire d'ajout
        return $this->render('entreprise/add.html.twig', [
            'formAddEntreprise' => $form->createView(),
            // Si y'a Id c'est q'on modifie (sinon renvoie false, = création), pour le titre de la page
            'edit' => $entreprise->getId()
        ]);
    }


    #[Route('/entreprise/{id}/delete', name: 'app_deleteEntreprise')]
    public function delete(EntityManagerInterface $entityManager, Entreprise $entreprise): Response {
    
        // Suppression
        $entityManager->remove($entreprise);
        // pas de persist() ici (uniquement pour ajout en BDD), execution de l'action avec flush
        $entityManager->flush();

        // Redirection sur la route d'affichage de la liste 
        return $this->redirectToRoute('app_entreprisesListe');
    }


    // Détail de l'entreprise (+ Récupération du tableau d'employés de l'entreprise: remplacé par entreprise.employes ans Twig)
    #[Route('/entrepriseDetail/{id}', name: 'app_entrepriseDetail')]
    public function entrepriseDetail(EntityManagerInterface $entityManager, int $id): Response
    {
        $repoEntreprise = $entityManager->getRepository(Entreprise::class);
        // $repoEmploye = $entityManager->getRepository(Employe::class);

        $entreprise = $repoEntreprise->find($id);
        // $employesList = $repoEmploye->findBy(['entreprise' => $entreprise->getId()]);

        return $this->render('entreprise/entrepriseDetail.html.twig', [
            // 'employesArray' => $employesList,
            'entreprise' => $entreprise
        ]);
    }
    


}
