<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categories;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;

    /**
     * @Route("/annonce")
     */
class AnnoncesController extends AbstractController
{
  
    /**
     * @Route("/", name="admin_annonces")
     */
    public function index(AnnoncesRepository $annorepo): Response
    {
        $annorepo = $this->getDoctrine()->getRepository(Annonces::class)->findAll();
        return $this->render('admin/annonces/index.html.twig',[
            'annorepo'=>$annorepo
        ]);
    }
    /**
     * @Route("/activer/{id}", name="annonce_activer")
     */

     public function active(Annonces $annonce){
       $annonce->setActive(($annonce->getActive())?true:false);
       $em = $this->getDoctrine()->getManager();
       $em->persist($annonce);
       $em->flush();

       return new Response("true");

    }
    /**
     * @Route("/ajouter/annonce", name="admin_ajouter_annonce")
     */

     public function ajouterannonce(Request $request) {

        $annonces = new Annonces();

        $form = $this->createForm(AnnoncesType::class,$annonces)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonces);
            $em->flush();

            return $this->redirectToRoute('admin_annonces');
        }
        return $this->render('admin/annonces/ajouter.html.twig',[
            'annonces'=>$annonces,
            'form'=>$form->createView()
        ]);
     }
     /**
      * @Route("/modifier/annonce/{id}", name="admin_modifier_annonce")
      */

      public function modifierannonce(Request $request,Annonces $annonce){

        $form= $this->createForm(AnnoncesType::class,$annonce)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            $this->addFlash('message','annonce est modifier');

            return $this->redirectToRoute('admin_annonces');
        }

        return $this->render('admin/annonces/modifie.html.twig',[
            'annonce'=>$annonce,
            'form'=>$form->createView()
        ]);

      }

      /**
       * @Route("/supprimer/annonce/{id}",name="admin_suprimer_annonce")
       */

       public function supprimer(Annonces $annonce) {
           $em = $this->getDoctrine()->getManager();
           $em->remove($annonce);
           $em->flush();

           return $this->redirectToRoute('admin/annonces/index.html.twig');
       }
}
