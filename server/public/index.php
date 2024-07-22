<?php

use Slim\Factory\AppFactory;
use DI\Container;
use App\Http\Controller\OpenAiController;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Tuupola\Middleware\CorsMiddleware;
use Http\Middleware\AuthMiddleware;
require __DIR__ . '/../vendor/autoload.php';
$container = new Container();
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

AppFactory::setContainer($container);

$app = AppFactory::create();
$app->add(AuthMiddleware::class);

$container->set(OpenAiController::class, function() {
    return new OpenAiController();
});

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => $_ENV['DB_CONNECTION'],
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$app->add(new CorsMiddleware([
    'origin' => ['*'],    // Permitir todos os origens
    'methods' => ['GET', 'POST', 'OPTIONS'],  // Métodos permitidos
    'headers.allow' => ['Authorization', 'Content-Type'],
    'headers.expose' => [],
    'credentials' => false,
    'cache' => 0,
]));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$routes = require __DIR__ .'/../app/routes/api.php';
$routes($app);
$app->addErrorMiddleware(true, true, true);

$app->run();
