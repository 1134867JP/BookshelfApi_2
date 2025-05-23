<?php
namespace Application\UseCase;

use Domain\Repository\BookRepositoryInterface;
use Domain\Entity\Book;
use Domain\Entity\BookValidator;

class CreateBook
{
    public function __construct(private BookRepositoryInterface $repo) {}

    public function execute(string $title, string $author, int $year): int
    {
        BookValidator::validate($title, $author, $year);
        // Verifica se já existe um livro com os mesmos dados
        $existing = array_filter(
            $this->repo->findAll(),
            fn($b) => $b->title() === $title && $b->author() === $author && $b->publishedYear() === $year
        );
        if (!empty($existing)) {
            throw new \Exception('Livro já cadastrado com os mesmos dados');
        }
        $book = new \Domain\Entity\Book(null, $title, $author, $year);
        return $this->repo->save($book);
    }
}
