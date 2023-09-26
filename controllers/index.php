<?php
require_once(__ROOT__ . '/controllers/Controller.php');
session_start();

class MainController extends Controller{
    public function get($request){
        $this->render('index',[]);
    }
}
?>
