<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Games;
use Symfony\Component\HttpFoundation\Response;

class GamesController extends AbstractController
{
    /**
     * @Route("/games/new/{id}/{genre}", name="new_game")
     */
    public function new($id, $genre, Request $request){
        $user = $this->getUser();
        $game = new Games();
        $game->setUserId($id);
        $game->setGenre($genre);
        $form = $this->createFormBuilder($game)
            ->add('title', TextType::class, array('label'=>'Tytuł'))
            ->add('body', TextType::class, array('label'=>'Treść'))
            ->add('save', SubmitType::class, array('label'=>'Zapisz'))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            $games = $this->getDoctrine()->getRepository(Games::class)->findAll();

            switch ($genre) {
                case 'fps_genre':
                    return $this->render('pages/fps_genre.html.twig', array('data' => $user->getUserName(), 'games'=>$games, 'id'=>$user->getId()));
                    break;
                case 'rpg_genre':
                    return $this->render('pages/rpg_genre.html.twig', array('data' => $user->getUserName(), 'games'=>$games, 'id'=>$user->getId()));
                    break;
                case 'farming_genre':
                    return $this->render('pages/farming_genre.html.twig', array('data'=>$user->getUserName(), 'games'=>$games, 'id'=>$user->getId()));
                    break;
            }


        }
        return $this->render('games/new.html.twig', array('form'=>$form->createView(), 'data'=>$user->getUserName()));

    }

    /**
     *@Route("/delete_game/{id}", name="delete_game")
     */
    public function delete($id){
        $game = $this->getDoctrine()->getRepository(Games::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($game);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('categories');
    }


}