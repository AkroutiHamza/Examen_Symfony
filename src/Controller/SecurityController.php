<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
 //methode qui permet d'inscrire un nouveau utilisateur.
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface  $em,
    UserPasswordEncoderInterface $encoder )
    {
        $user = new User();

        $form  = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
  //controle de saisie et encodage de mot de passe
        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);

          //l'objet $em sera affecté automatiquement grâce à l'injection des dépendances de symfony  
           $em->persist($user);
           $em->flush();       
        }
       return $this->render('security/registration.html.twig', 
                           ['form' =>$form->createView()]);
    }

/**
 * @Route("/connexion",name="security_login")
 */
public function login(AuthenticationUtils  $authenticationUtils)
{
     // recuperer l'erreur s'il y a en login
$error = $authenticationUtils->getLastAuthenticationError();

// dernier username saisie par l'utilisateur
$lastUsername = $authenticationUtils->getLastUsername();
    
  return $this->render('security/login.html.twig',
['lastUsername'=>$lastUsername,'error' => $error]);
}

/**
 * @Route("/deconnexion",name="security_logout")
 */
public function logout()
{ }


}
