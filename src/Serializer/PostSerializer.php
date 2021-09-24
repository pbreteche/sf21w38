<?php

namespace App\Serializer;

use App\Entity\Post;

class PostSerializer
{
    /** @var AuthorSerializer */
    private $authorSerializer;
    /** @var string */
    private $dateFormat;

    public function __construct(AuthorSerializer $authorSerializer, string $dateFormat)
    {
        $this->authorSerializer = $authorSerializer;
        $this->dateFormat = $dateFormat;
    }

    public function init()
    {
    }

    public function serialize(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'body' => $post->getBody(),
            'created_at' => $post->getCreatedAt()->format($this->dateFormat),
            'written_by' => $this->authorSerializer->serialize($post->getWrittenBy()),
        ];
    }
}
