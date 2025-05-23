<?php
namespace Infrastructure\Persistence;

use Domain\Repository\BookRepositoryInterface;
use Domain\Entity\Book;
use PDO;

class BookRepositoryMySQL implements BookRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function save(Book $book): int
    {
        if ($book->id() === null) {
            $stmt = $this->pdo->prepare("INSERT INTO books (title, author, published_year) VALUES (?, ?, ?)");
            $stmt->execute([$book->title(), $book->author(), $book->publishedYear()]);
            return (int)$this->pdo->lastInsertId();
        } else {
            $stmt = $this->pdo->prepare("UPDATE books SET title = ?, author = ?, published_year = ? WHERE id = ?");
            $stmt->execute([$book->title(), $book->author(), $book->publishedYear(), $book->id()]);
            return $book->id();
        }
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Book((int)$row['id'], $row['title'], $row['author'], (int)$row['published_year']) : null;
    }

    public function findAll(): array
    {
        $books = [];
        foreach ($this->pdo->query("SELECT * FROM books") as $row) {
            $books[] = new Book((int)$row['id'], $row['title'], $row['author'], (int)$row['published_year']);
        }
        return $books;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$id]);
    }
}
