<?php

namespace App\Factory;

use App\Entity\Post;
use Symfony\Component\Security\Core\Security;

class PostInitializer
{
    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function create(): Post
    {
        return (new Post())
            ->setCreatedAt()
            ->setWrittenBy($this->security->getUser()->getAuthor())
        ;
    }
}