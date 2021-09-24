<?php

namespace App\Serializer;

use App\Entity\Post;

class PostSerializer
{

    public function serialize(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'body' => $post->getBody(),
            'created_at' => $post->getCreatedAt()->format('c'),
            'written_by' => [
                'nickname' => $post->getWrittenBy()->getNickName()
            ]
        ];
    }
}