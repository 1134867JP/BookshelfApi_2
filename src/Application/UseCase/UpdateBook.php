<?php
namespace Application\UseCase;

use Domain\Repository\BookRepositoryInterface;
use Domain\Entity\Book;
use Domain\Entity\BookValidator;

class UpdateBook
{
    public function __construct(private BookRepositoryInterface $repo) {}

    public function execute(int $id, string $title, string $author, int $year): void
    {
        if (!is_int($id) || $id <= 0) {
            throw new \Exception('ID inválido');
        }
        BookValidator::validate($title, $author, $year);
        $book = $this->repo->findById($id);
        if (!$book) {
            throw new \Exception('Livro não encontrado');
        }
        $book = new \Domain\Entity\Book($id, $title, $author, $year);
        $this->repo->save($book);
    }
}
