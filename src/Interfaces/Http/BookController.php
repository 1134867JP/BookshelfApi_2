<?php
namespace Interfaces\Http;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Application\UseCase\CreateBook;
use Application\UseCase\ListBooks;
use Application\UseCase\UpdateBook;
use Application\UseCase\DeleteBook;

class BookController
{
    public function __construct(
        private CreateBook $createUC,
        private ListBooks $listUC,
        private UpdateBook $updateUC,
        private DeleteBook $deleteUC
    ) {}

    private function bookToArray($book): array
    {
        return [
            'id' => $book->id(),
            'title' => $book->title(),
            'author' => $book->author(),
            'published_year' => $book->publishedYear()
        ];
    }

    public function list(Request $request, Response $response): Response
    {
        $books = $this->listUC->execute();
        $booksArray = array_map(fn($book) => $this->bookToArray($book), $books);
        $response->getBody()->write(json_encode($booksArray));
        return $response->withHeader('Content-Type','application/json');
    }

    public function getOne(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $book = $this->listUC->executeOne($id);
        if (!$book) {
            $response->getBody()->write(json_encode(['error' => 'Livro não encontrado']));
            return $response->withHeader('Content-Type','application/json')->withStatus(404);
        }
        $bookArray = $this->bookToArray($book);
        $response->getBody()->write(json_encode($bookArray));
        return $response->withHeader('Content-Type','application/json')->withStatus(200);
    }

    public function create(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        try {
            $id = $this->createUC->execute($data['title'] ?? null, $data['author'] ?? null, isset($data['published_year']) ? (int)$data['published_year'] : null);
            $response->getBody()->write(json_encode(['id' => $id, 'message' => 'Criado com sucesso']));
            return $response->withHeader('Content-Type','application/json')->withStatus(201);
        } catch (\Exception $e) {
            $status = ($e->getMessage() === 'Campos obrigatórios: title, author, published_year' || $e->getMessage() === 'Dados inválidos para title, author ou published_year') ? 400 : 409;
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type','application/json')->withStatus($status);
        }
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();
        try {
            $this->updateUC->execute((int)$args['id'], $data['title'] ?? null, $data['author'] ?? null, isset($data['published_year']) ? (int)$data['published_year'] : null);
            $response->getBody()->write(json_encode(['message' => 'Atualização realizada com sucesso']));
            return $response->withHeader('Content-Type','application/json')->withStatus(200);
        } catch (\Exception $e) {
            $status = ($e->getMessage() === 'Campos obrigatórios: title, author, published_year' || $e->getMessage() === 'Dados inválidos para title, author ou published_year') ? 400 : 404;
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type','application/json')->withStatus($status);
        }
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $this->deleteUC->execute((int)$args['id']);
            $response->getBody()->write(json_encode(['message' => 'Exclusão realizada com sucesso']));
            return $response->withHeader('Content-Type','application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type','application/json')->withStatus(404);
        }
    }
}
