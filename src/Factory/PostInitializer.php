<?php

namespace App\Factory;

use App\Entity\Post;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        $author = $this->security->getUser()->getAuthor();

        if (!$author) {
            throw new AccessDeniedHttpException();
        }

        return (new Post())
            ->setCreatedAt()
            ->setWrittenBy($author)
        ;
    }
}