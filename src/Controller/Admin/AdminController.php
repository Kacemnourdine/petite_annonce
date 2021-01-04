<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/admin")
     */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_home")
     */
    public function index(CategoriesRepository $catsrepo): Response
    {
        $catsrepo = $this->getDoctrine()->getRepository(Categories::class)->findAll();
        return $this->render('admin/index.html.twig',[
            'catsrepo'=>$catsrepo
        ]);
    }

    /**
     * @Route("/ajouter/categorie", name="admin_ajouter_categorie")
     */

     public function ajoutercategorie(Request $request) {

        $categories = new Categories();

        $form = $this->createForm(CategoriesType::class,$categories)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();

            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/categorie/ajouter.html.twig',[
            'categories'=>$categories,
            'form'=>$form->createView()
        ]);
     }
     /**
      * @Route("/modifier/categorie/{id}", name="admin_modifier_categorie")
      */

      public function modifiercategorie(Request $request,Categories $categorie){

        $form= $this->createForm(CategoriesType::class,$categorie)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('message','categorie est modifier');

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('admin/categorie/modifie.html.twig',[
            'categorie'=>$categorie,
            'form'=>$form->createView()
        ]);

      }
}
