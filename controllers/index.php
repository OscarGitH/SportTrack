<?php
require_once(__ROOT__ . '/controllers/Controller.php');
session_start();

class MainController extends Controller{
    // cette fonction permet d'afficher la page d'accueil
    public function get($request){
        $this->render('index',[]);
    }
}
?>
