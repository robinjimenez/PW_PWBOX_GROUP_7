<?php

$app->add('PWBox\Controller\Middleware\SessionMiddleware');

$app->get('/hello/{name}',
    'PWBox\Controller\HelloController')
    ->add('PWBox\Controller\Middleware\UserLoggedMiddleware');

$app->post(
    '/user',
    'PWBox\Controller\PostUserController'
);