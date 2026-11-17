<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AjoutReparationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reparation;

final class BaseController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', []);
    }

    #[Route('/reparation', name: 'app_reparation')]
    public function reparation(Request $request, EntityManagerInterface $em): Response
    {
        $reparation= new Reparation();
        $form=$this->createForm(AjoutReparationType::class, $reparation);

        if ($request->isMethod('POST')){
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $reparation->setDateDepot(new \DateTimeImmutable());
                $em->persist($reparation);
                $em->flush();
                $this->addFlash('notice', 'Demande envoyée');
                return $this->redirectToRoute('app_reparation');
            }
        }
        return $this->render('base/reparation.html.twig', ['formReparation'=>$form->createView(),]);
    }
}
