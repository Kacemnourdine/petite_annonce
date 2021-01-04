<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Form\EditProfilrType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
     * @Route("/users")
     */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_home")
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig');
    }

    /**
     * @Route("/data", name="users_data")
    */
    public function usersdata(){
        return$this->render('users/data.html.twig');
    }

     /**
     * @Route("/data/download", name="users_data_download")
    */
    public function usersdatadownload(){

        $pdfoptions = new Options();

        $pdfoptions->set('defaultFont','Arial');
        $pdfoptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfoptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer'=>false,
                'verify_peer_name'=>false,
                'allow_self_signed'=>true
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html = $this->renderView('users/download.html.twig');

        $dompdf->loadHtml($html); 
        $dompdf->setPaper('A4','portrait');
        $dompdf->render();

        $fichier = 'user-data-'. $this->getUser()->getName() . '.pdf';

        $dompdf->stream($fichier, [
            'Attachement'=>true
        ]);

        return new Response();
    }

    /**
     * @Route("/ajouter/annonce", name="users_ajouter_annonces")
     */
    public function ajouterannonce(Request $request){
        $annonces = new Annonces();
        
        $form = $this->createForm(AnnoncesType::class,$annonces)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $annonces->setUsers($this->getUser());
            $annonces->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonces);
            $em->flush();

            return $this->redirectToRoute('users_home');
        }
        
        return $this->render('users/annonce/ajouter.html.twig',[
            'annonces'=>$annonces,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/modifier/profile" ,name="users_modifier_profile")
     */
    public function modifierprofile(Request $request) {
        $user =$this->getUser();

        $form = $this->createForm(EditProfilrType::class,$user)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            $this->addFlash('message','Profile à modifie');
            return $this->redirectToRoute('users_home');
        }
         return $this->render('users/editprofile.html.twig',[
             'user'=>$user,
             'form'=>$form->createView()
         ]);
    }

    /**
     * @Route("/modifier/pass/profile", name="users_pass_profile")
     */

     public function modifierpass(Request $request, UserPasswordEncoderInterface $PasswordEncoder) {

        if($request->isMethod('POST')){
           $em = $this->getDoctrine()->getManager();
           $user = $this->getUser();

           if($request->request->get('pass') == $request->request->get('pass2')){
               $user->setPassword($PasswordEncoder->encodePassword($user,$request->request->get('pass')));
               $this->addFlash('message','mot de passe est modifie');

               return $this->redirectToRoute('users_home');
           } else {
               $this->addFlash('error','mot de passe incorrect');
           }
        }

        return $this->render('users/editpass.html.twig');
     }

    }
