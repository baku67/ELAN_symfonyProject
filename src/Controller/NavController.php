<?php

    namespace App\Controller;

    use Doctrine\ORM\EntityManagerInterface;
    use App\Entity\Employe;
    use App\Entity\Entreprise;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class NavController extends AbstractController
    {

        #[Route('', name: 'app_homepage')]
        public function homepage(EntityManagerInterface $entityManager): Response
        {
            return $this->render('index.html.twig', [
                'testKey' => 'testData',
            ]);
            // return $this->redirectToRoute('homepage');
    
        }

    }

?>