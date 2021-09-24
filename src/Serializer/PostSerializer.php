<?php

namespace App\Serializer;

use App\Entity\Post;

class PostSerializer
{
    /** @var AuthorSerializer */
    private $authorSerializer;

    public function __construct(AuthorSerializer $authorSerializer)
    {
        $this->authorSerializer = $authorSerializer;
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
            'created_at' => $post->getCreatedAt()->format('c'),
            'written_by' => $this->authorSerializer->serialize($post->getWrittenBy()),
        ];
    }
}
