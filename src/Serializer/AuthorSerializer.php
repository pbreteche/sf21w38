<?php

namespace App\Serializer;

use App\Entity\Author;

class AuthorSerializer
{
    public function serialize(Author $author): array
    {
        return [
            'id' => $author->getId(),
            'nickname' => $author->getNickName(),
            'email' => $author->getEmail(),
        ];
    }
}
