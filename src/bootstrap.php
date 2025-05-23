<?php

declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv;
use Infrastructure\Persistence\PDOConnection;
use Domain\Repository\BookRepositoryInterface;
use Infrastructure\Persistence\BookRepositoryMySQL;
use Application\UseCase\CreateBook;
use Application\UseCase\ListBooks;
use Application\UseCase\UpdateBook;
use Application\UseCase\DeleteBook;
use Interfaces\Http\BookController;

// Carrega as variáveis do .env
Dotenv::createImmutable(__DIR__ . '/../')->load();

// Carrega configurações de DB
$settings = require __DIR__ . '/../config/settings.php';

// Configura o container de DI
$container = new Container();
$container->set(PDO::class, function() use ($settings) {
    return PDOConnection::make($settings['db']);
});
$container->set(BookRepositoryInterface::class, function($c) {
    return new BookRepositoryMySQL($c->get(PDO::class));
});
$container->set(CreateBook::class, function($c) {
    return new CreateBook($c->get(BookRepositoryInterface::class));
});
$container->set(ListBooks::class, function($c) {
    return new ListBooks($c->get(BookRepositoryInterface::class));
});
$container->set(UpdateBook::class, function($c) {
    return new UpdateBook($c->get(BookRepositoryInterface::class));
});
$container->set(DeleteBook::class, function($c) {
    return new DeleteBook($c->get(BookRepositoryInterface::class));
});
$container->set(BookController::class, function($c) {
    return new BookController(
        $c->get(CreateBook::class),
        $c->get(ListBooks::class),
        $c->get(UpdateBook::class),
        $c->get(DeleteBook::class)
    );
});

// Cria o app Slim
AppFactory::setContainer($container);
$app = AppFactory::create();

// Middlewares
$app->addBodyParsingMiddleware();

// Handler customizado para 404 em JSON
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(function ($request, $exception, $displayErrorDetails, $logErrors, $logErrorDetails) use ($app) {
    if ($exception instanceof Slim\Exception\HttpNotFoundException) {
        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(json_encode(['error' => 'Rota não encontrada']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
    // fallback para outros erros
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode([
        'error' => 'Erro interno',
        'message' => $displayErrorDetails ? $exception->getMessage() : 'Erro inesperado'
    ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
});

// Definição das rotas
$app->get   ('/books',          [BookController::class, 'list']);
$app->get   ('/books/{id}',     [BookController::class, 'getOne']);
$app->post  ('/books',          [BookController::class, 'create']);
$app->put   ('/books/{id}',     [BookController::class, 'update']);
$app->delete('/books/{id}',     [BookController::class, 'delete']);

return $app;
