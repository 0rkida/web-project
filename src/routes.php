<?php

$uri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($uri) {
    case '/':{
//        require 'controllers/home.php';
//        $homeController = new HomeController();
//        if ($requestMethod === 'GET') {
//
//            break;
//        }
//        else if($requestMethod === 'POST'){
//
//        }
        require 'home.html';
    }

    case '/register':{
        require 'controllers/registerController.php';
        $registerController = new RegisterController();
        if($requestMethod === 'GET'){
            $registerController->getView();
        }else if($requestMethod === 'POST'){
            $registerController->postRegister([
                'email' => $_POST['email'],
                'password' => $_POST['password']]
            );
            // ketu do behet redirect ne url-ne e radhes
        }
    }


}



//function routeHandler(){
//
//}
