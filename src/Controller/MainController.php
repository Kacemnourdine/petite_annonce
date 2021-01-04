<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\SearcheAnnonceType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AnnoncesRepository $annoncesrepo, Request $request): Response
    {
        $annonces = $annoncesrepo->findAll();

        $form = $this->createForm(SearcheAnnonceType::class);

        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $annoncesrepo->search(
                $search->get('mots')->getData(),
                $search->get('categories')->getData()
            );
        }
        return $this->render('main/index.html.twig', [
            'annonces' => $annonces,
            'form'=>$form->createView()
        ]);
    }
}
