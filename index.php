<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
define("__ROOT__", dirname(__FILE__));

// Configuration
require_once (__ROOT__.'/config.php');

// ApplicationController
require_once (CONTROLLERS_DIR.'/ApplicationController.php');

// Add routes here
ApplicationController::getInstance()->addRoute('/', CONTROLLERS_DIR.'/index.php');
ApplicationController::getInstance()->addRoute('apropos', CONTROLLERS_DIR.'/apropos.php');
ApplicationController::getInstance()->addRoute('user_add', CONTROLLERS_DIR.'/AddUserController.php');
ApplicationController::getInstance()->addRoute('connect', CONTROLLERS_DIR.'/ConnectUserController.php');

ApplicationController::getInstance()->addRoute('user_add_valid', VIEWS_DIR.'/user_add_valid.php');

ApplicationController::getInstance()->addRoute('user_add_invalid', VIEWS_DIR.'/user_add_invalid.php');
ApplicationController::getInstance()->addRoute('not_logged_in', VIEWS_DIR.'/not_logged_in.php');

ApplicationController::getInstance()->addRoute('disconnect', CONTROLLERS_DIR.'/DisconnectUserController.php');
ApplicationController::getInstance()->addRoute('upload', CONTROLLERS_DIR.'/UploadActivityController.php');
ApplicationController::getInstance()->addRoute('activities', CONTROLLERS_DIR.'/ListActivityController.php');
ApplicationController::getInstance()->addRoute('my_account', CONTROLLERS_DIR.'/MyAccountController.php');

// Process the request
ApplicationController::getInstance()->process();
?>