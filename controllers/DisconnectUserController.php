<?php
require(__ROOT__.'/controllers/Controller.php');

class DisconnectUserController extends Controller {
    public function get($request) {
        include("views/user_disconnect.php");
    }
}
?>