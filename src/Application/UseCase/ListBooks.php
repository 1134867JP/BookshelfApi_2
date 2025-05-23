<?php
namespace Application\UseCase;

use Domain\Repository\BookRepositoryInterface;

class ListBooks
{
    public function __construct(private BookRepositoryInterface $repo) {}

    public function execute(): array
    {
        $books = $this->repo->findAll();
        if (empty($books)) {
            throw new \Exception('Nenhum livro encontrado');
        }
        // Validação: todos os livros devem ter campos válidos
        foreach ($books as $book) {
            if (!is_string($book->title()) || !is_string($book->author()) || !is_int($book->publishedYear())) {
                throw new \Exception('Dados inválidos encontrados em um ou mais livros');
            }
        }
        return $books;
    }
    
    public function executeOne(int $id): \Domain\Entity\Book
    {
        if (!is_int($id) || $id <= 0) {
            throw new \Exception('ID inválido');
        }
        $book = $this->repo->findById($id);
        if (!$book) {
            throw new \Exception('Livro não encontrado');
        }
        // Validação: campos obrigatórios
        if (!is_string($book->title()) || !is_string($book->author()) || !is_int($book->publishedYear())) {
            throw new \Exception('Dados inválidos para o livro');
        }
        return $book;
    }
}
