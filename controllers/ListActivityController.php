<?php

require(__ROOT__.'/controllers/Controller.php');

class ListActivityController extends Controller {
    public function get($request) {
        include(__ROOT__.'/views/list_activities.php');
    }
}
?>