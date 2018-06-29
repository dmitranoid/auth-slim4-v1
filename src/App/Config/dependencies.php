<?php

// php-di configuration

use Psr\Container\ContainerInterface;
use function DI\create;
use function DI\get;
use function DI\factory;

return [
// Settings
    'settings' => require APP_DIR . '/Config/settings.php',
// Service providers
    \App\Infrastructure\Interfaces\View\ViewInterface::class => factory(function (ContainerInterface $c) use ($app) {
        $settings = $c->get('settings');
        $twig = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

        // Add extensions
        $twig->addExtension(new Slim\Views\TwigExtension($app->getRouter(), ''));
        $twig->addExtension(new Twig_Extension_Debug());
        $twig->addExtension(new Knlv\Slim\Views\TwigMessages(new Slim\Flash\Messages()));

        return new \App\Infrastructure\View\TwigView($twig);
    }),

    Slim\Flash\Messages::class => factory(function (ContainerInterface $c) {
        return new Slim\Flash\Messages;
    }),

    \Psr\Log\LoggerInterface::class => function (ContainerInterface $c) {
        $settings = $c->get('settings');
        $logger = new Monolog\Logger($settings['logger']['name']);
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
        return $logger;
    },

    \PDO::class => factory(function(ContainerInterface $c) {
        $db = $c->get('settings')['database'];
        $dsn = "{$db['driver']}:{$db['dbname']}";
        try {
            $pdo = new \PDO($dsn, $db['user'], $db['password']);
        } catch (\PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            throw $e;
        }
        foreach ($db['initCommands'] ?? [] as $command) {
            $pdo->exec($command);
        }
        return $pdo;
    }),

    \App\Infrastructure\Interfaces\Session\SessionInterface::class => factory(function(ContainerInterface $c){
        return new App\Infrastructure\Session\PhpSession();
    }),

    \App\Infrastructure\Interfaces\Auth\Auth::class => factory (function(ContainerInterface $c) {
        return new \App\Infrastructure\Auth\Auth(
            new \App\ReadModel\PdoFinder\User\PdoUserFinder($c->get('db')),
            new \App\ReadModel\PdoFinder\Application\PdoApplicationFinder($c->get('db')),
            new \App\Infrastructure\PasswordHasher\PasswordHasher(),
            $c->get('session')
        );
    }),

    'logger' => get(\Psr\Log\LoggerInterface::class),

    'db' => get(\PDO::class),

    'view' => get(\App\Infrastructure\View\TwigView::class),

    'flash' => get(Slim\Flash\Messages::class),

    'session' => get(\App\Infrastructure\Interfaces\Session\SessionInterface::class),

    'repositoryFactory' => function(ContainerInterface $c) {
        $db = $c->get('db');
        $repositoryFactory = new App\Infrastructure\Repository\PDORepositoryFactory($db);
        return $repositoryFactory;
    },

    'validatorFactory' => function(ContainerInterface $c) {
        $validatorFactory = new App\Validators\ValidatorFactory();
        return $validatorFactory;
    },

    'eventDispatcher' => factory(function($logger) {
        return new App\Dispatchers\LoggerEventDispatcher($logger);
    })->parameter('logger', get('logger')),

    'phpErrorHandler' => function (ContainerInterface $c) {
        return function ($request, $response, $error) use ($c) {
            return $c['response']
                ->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write('Something went wrong!');
        };
    },
];