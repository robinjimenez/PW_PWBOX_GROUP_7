<?php
$app->add('PWBox\Controller\Middleware\SessionMiddleware');

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
    'PWBox\Controller\RegisterController:registerAction')
    ->add('PWBox\Controller\Middleware\RegisterValidationsMiddleware');

//Ruta per quan l'usuari ha fet login -> Accedeix a dashboard (user)
$app->post(
    '/user',
    'PWBox\Controller\LoginController:loginAction')
    ->add('PWBox\Controller\Middleware\LoginValidationsMiddleware');

$app->get(
    '/profile',
    'PWBox\Controller\ProfileController')
    ->add('PWBox\Controller\Middleware\UserLoggedMiddleware');

$app->post(
    '/profile',
    'PWBox\Controller\ProfileController:updateProfileAction')
    ->add('PWBox\Controller\Middleware\UserLoggedMiddleware');

$app->get(
    '/dashboard',
    'PWBox\Controller\FileController')
    ->add('PWBox\Controller\Middleware\UserLoggedMiddleware');

$app->post(
    '/dashboard',
    'PWBox\Controller\FileController:uploadFileAction')
    ->add('PWBox\Controller\Middleware\UserLoggedMiddleware');

$app->post('/',
    'PWBox\Controller\ProfileController:deleteProfileAction');


