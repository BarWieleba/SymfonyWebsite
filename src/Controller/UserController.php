<?php


namespace App\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController{

    /**
     * @Route("/users", name="signingUP")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response{
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('userName', TextType::class, array('label'=>'Nazwa użytkownika'))
            ->add('fullName', TextType::class, array('label'=>'Imię i nazwisko'))
            ->add('email', EmailType::class, array('label'=>'Adres e-mail'))
            ->add('password', PasswordType::class, array('label'=>'Hasło'))
            ->add('save', SubmitType::class, array('label'=>'Zatwierdź'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            ));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->render('index.html.twig', array('data'=>''));
        }
        return $this->render('users/signUp.html.twig', array('form'=>$form->createView(), 'data'=>''));
    }


    /**
     *@Route("/delete/{id}", name="delete")
     */
    public function delete($id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('app_logout');
    }


    /**
     * @Route("/users/edit/{id}", name="edit_profile")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id): Response{
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createFormBuilder($user)
            ->add('userName', TextType::class, array('label'=>'Nazwa użytkownika'))
            ->add('fullName', TextType::class, array('label'=>'Imię i nazwisko'))
            ->add('email', EmailType::class, array('label'=>'Adres e-mail'))
            ->add('password', PasswordType::class, array('label'=>'Hasło'))
            ->add('save', SubmitType::class, array('label'=>'Zatwierdź'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()
            ));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->render('index.html.twig', array('data'=>$this->getUser()->getID()));
        }
        return $this->render('users/edit.html.twig', array('form'=>$form->createView(), 'data'=>''));
    }


}