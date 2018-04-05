<?php

$container = $app->getContainer();

$container['view'] = function($container) {


    $view = new \Slim\Views\Twig(__DIR__ . '/../src/view/templates', [
        //'cache' => __DIR__ . '/../var/cache/' // ho comentem perquè no guardi cache
    ]);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};