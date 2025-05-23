<?php
namespace Application\UseCase;

use Domain\Repository\BookRepositoryInterface;

class DeleteBook
{
    public function __construct(private BookRepositoryInterface $repo) {}

    public function execute(int $id): void
    {
        if (!is_int($id) || $id <= 0) {
            throw new \Exception('ID inválido');
        }
        $book = $this->repo->findById($id);
        if (!$book) {
            throw new \Exception('Livro não encontrado');
        }
        $this->repo->delete($id);
    }
}
