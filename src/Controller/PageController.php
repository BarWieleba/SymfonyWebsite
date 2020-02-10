<?php


namespace App\Controller;

use App\Entity\Games;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;


class PageController extends AbstractController{
    /**
     * @Route("/", name="index")
     */
    public function index(){

        if($user = $this->getUser()){
            return $this->render('index.html.twig', array('data'=>$user->getUsername()));
        }
        else{
            return $this->render('index.html.twig', array('data'=>''));
        }

    }

    /**
     * @Route("/pages/categories/", name="categories")
     */
    public function categories(){

        $package = new Package(new EmptyVersionStrategy());

        $content = $package->getUrl('/farming_genre_cropped.png');

        if($user = $this->getUser()){
            return $this->render('pages/categories.html.twig', array('data'=>$user->getUsername(), 'content'=>$content));
        }
        else{
            return $this->render('pages/categories.html.twig', array('data'=>''));
        }
    }

    /**
     * @Route("/pages/my_account", name="my_account")
     */
    public  function my_account(){
        if($user = $this->getUser()){
            $content = array(
                'id'=>$user->getId(),
                'userName'=>"Nazwa użytkownika: ".$user->getUsername(),
                'fullName'=>"Imię i nazwisko: ".$user->getFullname(),
                'email'=>"Adres email: ".$user->getemail()
            );

            return $this->render('pages/my_account.html.twig', array('data'=>$user->getUsername(), 'contents'=>$content));
        }
        else{
            return $this->render('pages/my_account.html.twig', array('data'=>''));
        }
    }

    /**
     * @Route("pages/rpg_genre", name="rpg_genre")
     */
    public function showRpg(){
        $games = $this->getDoctrine()->getRepository(Games::class)->findAll();
        if($user = $this->getUser()){
            return $this->render('pages/rpg_genre.html.twig', array('data'=>$user->getUsername(), 'games'=>$games, 'id'=>$user->getId(), 'genre'=>'rpg_genre'));
        }
        else {
            return $this->render('pages/rpg_genre.html.twig', array('data' => '', 'games'=>$games));
        }
    }

    /**
     * @Route("pages/fps_genre", name="fps_genre")
     */
    public function showFps(){
        $games = $this->getDoctrine()->getRepository(Games::class)->findAll();
        if($user = $this->getUser()){
            return $this->render('pages/fps_genre.html.twig', array('data'=>$user->getUsername(), 'games'=>$games, 'id'=>$user->getId(), 'genre'=>'rpg_genre'));
        }
        else {
            return $this->render('pages/fps_genre.html.twig', array('data' => '', 'games'=>$games));
        }
    }

    /**
     * @Route("pages/farming_genre", name="farming_genre")
     */
    public function showFarming(){
        $games = $this->getDoctrine()->getRepository(Games::class)->findAll();
        if($user = $this->getUser()){
            return $this->render('pages/farming_genre.html.twig', array('data'=>$user->getUsername(), 'games'=>$games, 'id'=>$user->getId(), 'genre'=>'rpg_genre'));
        }
        else {
            return $this->render('pages/farming_genre.html.twig', array('data' => '', 'games'=>$games));
        }
    }

}