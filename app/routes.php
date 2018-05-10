<?php

//Ruta per accedir a la Landing page
$app->get('/',
    'PWBox\Controller\HelloController');

//Ruta per accedir a la pàgina de registre
$app->get(
    '/register',
    'PWBox\Controller\RegisterController'
);

//Ruta per accedir a la pàgina de login
$app->get(
    '/login',
    'PWBox\Controller\LoginController'
);

//Ruta per quan l'usuari s'ha registrat -> Accedeix a login
$app->post(
    '/login',
    'PWBox\Controller\PostUserController:registerAction')
    ->add('PWBox\Controller\Middleware\RegisterValidationsMiddleware');

//Ruta per quan l'usuari ha fet login -> Accedeix a dashboard (user)
$app->post(
    '/user',
    'PWBox\Controller\PostUserController:loginAction')
    ->add('PWBox\Controller\Middleware\LoginValidationsMiddleware');
    //->add('PWBox\Controller\Middleware\SessionMiddleware');
/*
$app->get(
  '/user',
  'PWBox\Controller\PostUserController:indexAction'
);*/

/*$app->get('/hello/{name}',
    'PWBox\Controller\HelloController');
    //->add('PWBox\Controller\Middleware\UserLoggedMiddleware');
*/

