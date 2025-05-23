<?php
namespace Domain\Entity;

class BookValidator
{
    public static function validate(string $title = null, string $author = null, int $year = null): void
    {
        if (empty($title) || empty($author) || $year === null) {
            throw new \Exception('Campos obrigatórios: title, author, published_year');
        }
        if (!is_string($title) || !is_string($author) || !is_int($year)) {
            throw new \Exception('Dados inválidos para title, author ou published_year');
        }
    }
}
