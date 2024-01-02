<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entitymanager;
    public function __construct(EntityManagerInterface $entitymanager)
    {
        $this->entitymanager = $entitymanager;
    }

    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $u = new User();
        $form = $this->createForm(RegisterType::class, $u) ;

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $u = $form->getData();
            $password = $encoder->hashPassword($u, $u->getPassword());
            $u->setPassword($password);
           
            $this->entitymanager->persist($u);
            $this->entitymanager->flush();
            
        }
        
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
