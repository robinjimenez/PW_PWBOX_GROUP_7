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

$container['flash'] = function($container) {
    return new \Slim\Flash\Messages();
};

$container['doctrine'] = function($container) {

  $config = new \Doctrine\DBAL\Configuration();
  $conn = \Doctrine\DBAL\DriverManager::getConnection(
      $container->get('settings')['database'],$config);
  return $conn;
};

//Registre de la implementació de la BBDD
$container['user_repository'] = function($container) {//Taula de users
  $repository = new PWBox\Model\Implementation\DoctrineUserRepository(
      $container->get('doctrine')
  );
  return $repository;
};

//Register user post service
$container['post_user_use_case'] = function($container) {
  $useCase = new PWBox\Model\UseCase\RegisterUseCase($container->get('user_repository'));
  return $useCase;
};

//login user post service
$container['login_user_use_case'] = function($container) {
    $useCase = new PWBox\Model\UseCase\LoginUseCase($container->get('user_repository'));//3)Es crea una nova instància de la classe LoginUseCase. És invokable.
    return $useCase;
};

//get user info service
$container['get_user_use_case'] = function ($container) {
    $useCase = new PWBox\Model\UseCase\GetUserUseCase($container->get('user_repository'));
    return $useCase;
};

//update email service
$container['update_email_use_case'] = function ($container) {
    $useCase = new PWBox\Model\UseCase\UpdateEmailUseCase($container->get('user_repository'));
    return $useCase;
};


