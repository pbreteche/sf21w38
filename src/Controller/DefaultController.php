<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function homepage(): Response
    {
        $response = new Response(
            'Ceci est le corps de ma réponse HTTP',
            Response::HTTP_OK,
            [
                'Content-type' => 'text/plain',
            ]
        );

        // Toute manipulation de la réponse HTTP doit se faire
        // au travers de cet objet "response"
        // Ceci implique l'interdiction formelle d'utiliser:
        // echo, print, print_r, var_dump, header, http_response_code
        $response
            ->setContent('contenu écrasant le précédent')
            ->setCharset('utf8')
            ->headers->set('Content-type', 'text/html')
        ;

        return $response;
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
