<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function homepage(): Response
    {
        return new Response(
            'Ceci est le corps de ma réponse HTTP',
            Response::HTTP_OK,
            [
                'Content-type' => 'text/plain',
            ]
        );
    }

    public function hello(string $name): Response
    {
        return new Response('Bonjour '.$name.' !');
    }

    public function article(int $id): Response
    {
        return new Response('Contenu de l\'article '.$id);
    }
}
