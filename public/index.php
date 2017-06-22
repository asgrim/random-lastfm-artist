<?php
declare(strict_types=1);

use Asgrim\RandomArtist\Middleware;
use Zend\Expressive\AppFactory;

chdir(dirname(__DIR__));
require __DIR__ . '/../vendor/autoload.php';

/** @var  $container */
$container = require __DIR__ . '/../config/container.php';

$app = AppFactory::create($container);

$app->get('/', Middleware\IndexAction::class);

$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();
$app->run();
