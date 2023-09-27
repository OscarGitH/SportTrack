<?php
require(__ROOT__.'/controllers/Controller.php');
class AProposController extends Controller{

    // cette fonction permet d'afficher la page à propos
    public function get($request) {
        $request = ['firstname' =>'PAVOINE, PIERRE', 'lastname' => 'Oscar, Noé'];
        $this->render('apropos', $request);
    }
}
?>