<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

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

$capsule->setAsGlobal();
$capsule->bootEloquent();

$app = new Container();
Facade::setFacadeApplication($app);

$app->singleton('db', function () use ($capsule) {
    return $capsule->getDatabaseManager();
});
$app->singleton('db.schema', function ($app) {
    return $app['db']->connection()->getSchemaBuilder();
});

$migrationRepository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migrations');

if (!$migrationRepository->repositoryExists()) {
    $migrationRepository->createRepository();
}

$filesystem = new Filesystem();
$migrator = new Migrator($migrationRepository, $capsule->getDatabaseManager(), $filesystem);

try {
    $migrator->run(__DIR__ . '/../migrations');
    echo "Migrações executadas com sucesso.";
} catch (\Exception $e) {
    echo "Erro ao executar migrações: " . $e->getMessage();
}
