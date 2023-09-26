<?php
require(__ROOT__.'/controllers/Controller.php');
class AProposController extends Controller{

    public function get($request) {
        $request = ['firstname' =>'PAVOINE, PIERRE', 'lastname' => 'Oscar, Noé'];
        $this->render('apropos', $request);
    }
}
?>