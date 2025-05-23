<?php
namespace Domain\Entity;

class Book
{
    private ?int $id;
    private string $title;
    private string $author;
    private int $publishedYear;

    public function __construct(?int $id, string $title, string $author, int $publishedYear)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->publishedYear = $publishedYear;
    }

    public function id(): ?int { return $this->id; }
    public function title(): string { return $this->title; }
    public function author(): string { return $this->author; }
    public function publishedYear(): int { return $this->publishedYear; }
}
