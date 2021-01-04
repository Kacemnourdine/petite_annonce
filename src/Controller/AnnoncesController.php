<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
 
    /**
     * @Route("/articles")
     */

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="article_index")
     */
    public function index(): Response
    {
        return $this->render('annonces/index.html.twig', [
            'controller_name' => 'AnnoncesController',
        ]);
    }
    /**
     * @Route("/details/{slug}", name="article_detail")
     */
    public function detail($slug , AnnoncesRepository $annonces) {

        $annonces =$this->getDoctrine()->getRepository(Annonces::class)->findBy(['slug'=>$slug]);
        if(!$annonces) {
            throw new NotFoundHttpException('pas d\'annonce');
        }
        return $this->render('annonces/detail.html.twig',compact('annonces'));
    }

    /**
     * @Route("/ajouter/favoris/{id}", name="ajouter_favoris")
     */
    public function ajouterfavoris(Annonces $annonce) {

        if(!$annonce) {
            throw new NotFoundHttpException('pas d\'annonce');
        }
        $annonce->addFavori($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/retrait/favoris/{id}", name="retrait_favoris")
     */
    public function retraitfavoris(Annonces $annonce) {

        if(!$annonce) {
            throw new NotFoundHttpException('pas d\'annonce');
        }
        $annonce->removeFavori($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
