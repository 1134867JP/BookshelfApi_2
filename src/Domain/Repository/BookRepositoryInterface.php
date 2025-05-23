<?php
namespace Domain\Repository;

use Domain\Entity\Book;

interface BookRepositoryInterface
{
    public function save(Book $book): int;
    public function findById(int $id): ?Book;
    public function findAll(): array;
    public function delete(int $id): void;
}
