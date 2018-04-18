<?php

$app->get('/hello/{name}',
    'PWBox\Controller\HelloController')
    ->add('PWBox\Controller\Middleware\TestMiddleware')
    ->add('PWBox\Controller\Middleware\TestMiddleware2');

$app->post(
    '/user',
    'PWBox\Controller\PostUserController'
);