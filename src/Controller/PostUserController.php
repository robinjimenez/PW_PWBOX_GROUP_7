<?php

namespace PWBox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class PostUserController {

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response) {
        $messages = $this->container->get('flash')->getMessages();
        $registerMessages = isset($messages['register'])?$messages['register']:[];
        return $this->container->get('view')
            ->render($response, 'register.twig', ['messages' => $registerMessages,]);
    }

    public function registerAction(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();
            // TODO: Validate!
            // isset($data['email']) ... etc
            $service = $this->container->get('post_user_use_case');
            $service($data);
            $this->container->get('flash')->addMessage('register','User registered');
            return $response->withStatus(302)->withHeader('Location','/user');
        } catch (\Exception $e)  {
            return $this->container->get('view')
                ->render($response, 'register.twig', []);
        }
        return $response;
    }

}